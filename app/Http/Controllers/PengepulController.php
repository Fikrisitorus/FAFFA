<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjemputan;
use App\Models\PenjemputanSampahDetail;
use App\Models\JenisSampah;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;

class PengepulController extends Controller
{
    /**
     * Ambil order (FCFS - siapa cepat dia dapat)
     */
    public function ambilOrder(Penjemputan $penjemputan)
    {
        // Cek apakah order masih available
        if ($penjemputan->status !== 'pending') {
            \Filament\Notifications\Notification::make()
                ->title('Gagal Mengambil Order')
                ->body('Order sudah diambil pengepul lain!')
                ->danger()
                ->send();

            return back();
        }

        // Cek apakah user adalah pengepul
        if (!Auth::user()->hasRole('pengepul')) {
            \Filament\Notifications\Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda tidak memiliki akses untuk mengambil order!')
                ->danger()
                ->send();

            return back();
        }

        // Langsung ambil order (FCFS)
        $penjemputan->update([
            'status' => 'assigned',
            'pengepul_id' => Auth::id(),
            'assigned_at' => now()
        ]);

        // Kirim notifikasi sukses
        \Filament\Notifications\Notification::make()
            ->title('Order Berhasil Diambil!')
            ->body('Sekarang klik "Verifikasi Berat" untuk memulai proses.')
            ->success()
            ->send();

        // Redirect ke dashboard
        return redirect()->route('filament.admin.pages.dashboard-pengepul');
    }


    /**
     * Halaman verifikasi berat
     */
    public function verifikasiBerat(Penjemputan $penjemputan)
    {
        // Cek apakah pengepul yang mengambil order ini
        if ($penjemputan->pengepul_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke order ini!');
        }

        // Cek apakah status sudah assigned atau on_progress
        if (!in_array($penjemputan->status, ['assigned', 'on_progress'])) {
            return back()->with('error', 'Order belum diambil atau sudah selesai!');
        }

        // Jika status masih assigned, ubah ke on_progress
        if ($penjemputan->status === 'assigned') {
            $penjemputan->update([
                'status' => 'on_progress',
                'waktu_mulai' => now()
            ]);
        }

        // Cek apakah sudah ada verifikasi berat
        if ($penjemputan->status === 'weight_verified') {
            return redirect()->route('pengepul.pembayaran', $penjemputan->id)
                ->with('info', 'Berat sudah terverifikasi. Silakan lanjut ke pembayaran.');
        }

        $jenisSampah = JenisSampah::all();

        // Ambil data sampah detail yang sudah diinput kelompok untuk pre-fill
        $sampahDetailsKelompok = $penjemputan->sampahDetails;

        return view('pengepul.verifikasi-berat', compact('penjemputan', 'jenisSampah', 'sampahDetailsKelompok'));
    }

    /**
     * Halaman pembayaran (setelah berat terverifikasi)
     */
    public function pembayaran(Penjemputan $penjemputan)
    {
        try {
            \Log::info('[pembayaran] enter', [
                'penjemputan_id' => $penjemputan->id,
                'auth_id' => Auth::id(),
                'pengepul_id' => $penjemputan->pengepul_id,
                'status' => $penjemputan->status,
                'details_count' => $penjemputan->sampahDetails->count(),
                'verified_count' => $penjemputan->sampahDetails->where('berat_verifikasi', '>', 0)->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('[pembayaran] error in logging', ['error' => $e->getMessage()]);
        }
        // Cek apakah pengepul yang mengambil order ini
        if ($penjemputan->pengepul_id !== Auth::id()) {
            \Log::warning('[pembayaran] unauthorized access', [
                'penjemputan_id' => $penjemputan->id,
                'auth_id' => Auth::id(),
                'pengepul_id' => $penjemputan->pengepul_id,
            ]);
            return back()->with('error', 'Anda tidak memiliki akses ke order ini!');
        }

        // Cek apakah berat sudah terverifikasi
        if (!in_array($penjemputan->status, ['weight_verified', 'on_progress'])) {
            \Log::warning('[pembayaran] status not verified', [
                'penjemputan_id' => $penjemputan->id,
                'status' => $penjemputan->status,
            ]);
            return back()->with('error', 'Berat sampah belum terverifikasi!');
        }

        // Cek apakah ada data verifikasi berat
        $hasVerifiedWeight = $penjemputan->sampahDetails->where('berat_verifikasi', '>', 0)->count() > 0;
        if (!$hasVerifiedWeight) {
            \Log::warning('[pembayaran] no verified weight data', [
                'penjemputan_id' => $penjemputan->id,
            ]);
            return back()->with('error', 'Berat sampah belum terverifikasi!');
        }

        // Hitung total harga berdasarkan sampah detail yang sudah ada
        $totalHarga = 0;
        $itemDetails = [];

        foreach ($penjemputan->sampahDetails as $detail) {
            // Hanya proses item yang memiliki berat verifikasi > 0
            if ((float) $detail->berat_verifikasi <= 0) {
                continue;
            }

            $hargaPerKg = (float) ($detail->jenisSampah->harga ?? 0);
            $subtotal = (float) $detail->berat_verifikasi * $hargaPerKg;
            $totalHarga += $subtotal;

            $itemDetails[] = [
                'id' => $detail->jenis_sampah_id,
                'price' => $hargaPerKg,
                'quantity' => (float) $detail->berat_verifikasi,
                'name' => $detail->jenisSampah->nama,
                'category' => 'Waste Collection',
                'merchant_name' => 'Bismillah Waste Management'
            ];
        }

        // Cek apakah ada item valid
        if (empty($itemDetails)) {
            \Log::warning('[pembayaran] empty itemDetails', [
                'penjemputan_id' => $penjemputan->id,
            ]);
            return back()->with('error', 'Tidak ada sampah yang terverifikasi untuk dibayar!');
        }

        // Cek apakah total harga valid
        if ($totalHarga < 0.01) {
            \Log::warning('[pembayaran] total too small', [
                'penjemputan_id' => $penjemputan->id,
                'total_harga' => $totalHarga,
            ]);
            return back()->with('error', 'Total pembayaran terlalu kecil (minimum Rp 0.01)!');
        }

        // Debug logging
        \Log::info('Midtrans payment data:', [
            'penjemputan_id' => $penjemputan->id,
            'total_harga' => $totalHarga,
            'item_details' => $itemDetails,
            'item_count' => count($itemDetails)
        ]);

        // Snap token akan dibuat setelah user memilih opsi pembayaran
        // Untuk sekarang, kirim data yang diperlukan ke view
        return view('pengepul.pembayaran', compact('penjemputan', 'totalHarga'));
    }

    /**
     * Get snap token berdasarkan opsi pembayaran
     */
    public function getSnapToken(Request $request, Penjemputan $penjemputan)
    {
        $request->validate([
            'payment_option' => 'required|in:take_all,donate_all,donate_partial',
            'donation_amount' => 'nullable|numeric|min:0'
        ]);

        // Hitung total harga berdasarkan sampah detail yang sudah ada
        $totalHarga = 0;
        foreach ($penjemputan->sampahDetails as $detail) {
            if ((float) $detail->berat_verifikasi <= 0) {
                continue;
            }
            $hargaPerKg = (float) ($detail->jenisSampah->harga ?? 0);
            $subtotal = (float) $detail->berat_verifikasi * $hargaPerKg;
            $totalHarga += $subtotal;
        }

        // Hitung harga yang akan dibayar ke nasabah berdasarkan opsi
        $paymentOption = $request->payment_option;
        $donationAmount = 0;
        $nasabahAmount = 0;

        if ($paymentOption === 'take_all') {
            $donationAmount = 0;
            $nasabahAmount = $totalHarga;
        } elseif ($paymentOption === 'donate_all') {
            $donationAmount = $totalHarga;
            $nasabahAmount = 0;
        } elseif ($paymentOption === 'donate_partial') {
            $donationAmount = (float) ($request->donation_amount ?? 0);
            $nasabahAmount = $totalHarga - $donationAmount;
        }

        // Midtrans menggunakan harga yang akan dibayar ke nasabah
        // Untuk opsi "donate_all", kita tetap perlu proses minimal amount untuk QRIS
        if ($nasabahAmount <= 0) {
            $aggregatePrice = 10000; // Minimal amount untuk QRIS (Rp 10.000)
        } else {
            $aggregatePrice = (int) round($nasabahAmount);
        }

        // Generate order ID
        $orderId = 'PENJEMPUTAN-' . $penjemputan->id . '-' . time();

        // Customer details
        $nasabah = $penjemputan->kelompok->nasabah->first();
        $customerDetails = [
            'first_name' => $penjemputan->kelompok->nama ?? 'Customer',
            'last_name' => '',
            'email' => $nasabah->email ?? 'customer@example.com',
            'phone' => $penjemputan->kelompok->no_telepon ?? '081234567890',
            'billing_address' => [
                'first_name' => $penjemputan->kelompok->nama ?? 'Customer',
                'last_name' => '',
                'address' => $penjemputan->alamat_penjemputan,
                'city' => 'Jakarta',
                'postal_code' => '12345',
                'phone' => $penjemputan->kelompok->no_telepon ?? '081234567890',
                'country_code' => 'IDN'
            ]
        ];

        // Item details untuk Midtrans
        $itemDetails = [
            [
                'id' => 'PENJ-' . $penjemputan->id,
                'price' => $aggregatePrice,
                'quantity' => 1,
                'name' => 'Pembayaran penjemputan sampah #' . $penjemputan->id,
                'category' => 'Waste Collection',
                'merchant_name' => 'Bismillah Waste Management'
            ]
        ];

        // Create Midtrans service
        $midtransService = new MidtransService();

        try {
            // Debug logging
            \Log::info('Creating Midtrans Snap Token', [
                'order_id' => $orderId,
                'amount' => $aggregatePrice,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'app_url' => config('app.url')
            ]);

            // Create snap token
            $snapToken = $midtransService->createSnapToken(
                $orderId,
                $aggregatePrice,
                $customerDetails,
                $itemDetails,
                $penjemputan->id
            );

            // Update penjemputan with order ID dan opsi pembayaran
            $penjemputan->update([
                'midtrans_order_id' => $orderId,
                'total_amount' => $aggregatePrice,
                'payment_status' => 'pending',
                'payment_option' => $paymentOption,
                'donation_amount' => $donationAmount,
                'nasabah_amount' => $nasabahAmount
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'nasabah_amount' => $nasabahAmount,
                'donation_amount' => $donationAmount,
                'total_harga' => $totalHarga
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses verifikasi berat saja (tanpa pembayaran)
     */
    public function prosesVerifikasiBerat(Request $request, Penjemputan $penjemputan)
    {
        // Debug file upload
        \Log::info('File upload debug:', [
            'has_file' => $request->hasFile('gambar'),
            'gambar_count' => $request->hasFile('gambar') ? count($request->file('gambar')) : 0,
            'all_files' => $request->allFiles()
        ]);

        $request->validate([
            'jenis_sampah_id' => 'required|array',
            'jenis_sampah_id.*' => 'required|exists:jenis_sampah,id',
            'berat_verifikasi' => 'required|array',
            'berat_verifikasi.*' => 'required|numeric|min:0.1',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:500',
            'gambar' => 'nullable|array',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Hitung total berat final
        $totalBeratFinal = array_sum($request->berat_verifikasi);

        // Hitung perbedaan berat
        $beratDifference = null;
        if ($penjemputan->estimasi_berat > 0) {
            $beratDifference = $penjemputan->estimasi_berat - $totalBeratFinal;
        }

        // Update penjemputan - hanya verifikasi berat
        $penjemputan->update([
            'status' => 'weight_verified',
            'berat_final' => $totalBeratFinal,
            'weight_status' => 'verified',
            'berat_difference' => $beratDifference
        ]);

        // Update sampah detail existing dengan berat verifikasi (TIDAK menghapus data nasabah)
        foreach ($request->jenis_sampah_id as $index => $jenisSampahId) {
            $beratVerifikasi = $request->berat_verifikasi[$index];
            $catatan = $request->catatan[$index] ?? null;

            // Handle file upload
            $gambarPath = null;
            if ($request->hasFile('gambar') && isset($request->gambar[$index]) && $request->gambar[$index]) {
                $file = $request->gambar[$index];
                \Log::info("Processing file for index $index:", [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_mime' => $file->getMimeType(),
                    'is_valid' => $file->isValid()
                ]);

                if ($file->isValid()) {
                    $gambarPath = $file->store('penjemputan-gambar', 'public');
                    \Log::info("File stored successfully: $gambarPath");
                } else {
                    \Log::error("File upload failed for index $index:", [
                        'error' => $file->getError(),
                        'error_message' => $file->getErrorMessage()
                    ]);
                }
            } else {
                \Log::info("No file for index $index");
            }

            // Cari detail yang sudah ada berdasarkan jenis_sampah_id
            $detail = $penjemputan->sampahDetails->where('jenis_sampah_id', $jenisSampahId)->first();

            if ($detail) {
                // Update data existing (data nasabah tetap ada)
                $detail->update([
                    'berat_verifikasi' => $beratVerifikasi,
                    'catatan' => $catatan,
                    'gambar' => $gambarPath
                ]);
            } else {
                // Jika tidak ada data existing, buat baru (untuk jenis sampah yang ditambah pengepul)
                PenjemputanSampahDetail::create([
                    'penjemputan_id' => $penjemputan->id,
                    'jenis_sampah_id' => $jenisSampahId,
                    'berat_nasabah' => 0, // Tidak ada data nasabah
                    'berat_verifikasi' => $beratVerifikasi,
                    'catatan' => $catatan,
                    'gambar' => $gambarPath
                ]);
            }
        }

        // Tambahkan notification untuk Filament
        if (class_exists('\Filament\Notifications\Notification')) {
            \Filament\Notifications\Notification::make()
                ->title('Berhasil')
                ->body('Berat sampah berhasil diverifikasi! Total berat: ' . number_format($totalBeratFinal, 2) . ' kg. Sekarang Anda bisa proses pembayaran.')
                ->success()
                ->send();
        }

        return redirect()->route('filament.admin.pages.dashboard-pengepul')
            ->with('success', 'Berat sampah berhasil diverifikasi! Sekarang Anda bisa proses pembayaran.');
    }

    /**
     * Proses pembayaran (setelah berat terverifikasi)
     */
    public function prosesPembayaran(Request $request, Penjemputan $penjemputan)
    {
        // Cek apakah berat sudah terverifikasi
        if (!in_array($penjemputan->status, ['weight_verified', 'on_progress'])) {
            return back()->with('error', 'Berat sampah belum terverifikasi!');
        }

        // Cek apakah ada data verifikasi berat
        $hasVerifiedWeight = $penjemputan->sampahDetails->where('berat_verifikasi', '>', 0)->count() > 0;
        if (!$hasVerifiedWeight) {
            return back()->with('error', 'Berat sampah belum terverifikasi!');
        }

        $request->validate([
            'payment_option' => 'required|in:take_all,donate_all,donate_partial',
            'donation_amount' => 'nullable|numeric|min:0'
        ]);

        \Log::info('[prosesPembayaran] Payment options received:', [
            'payment_option' => $request->payment_option,
            'donation_amount' => $request->donation_amount,
            'penjemputan_id' => $penjemputan->id
        ]);

        $totalHarga = 0;

        // Hitung total harga berdasarkan sampah detail yang sudah ada
        foreach ($penjemputan->sampahDetails as $detail) {
            $hargaPerKg = (float) ($detail->jenisSampah->harga ?? 0);
            $totalHarga += (float) $detail->berat_verifikasi * $hargaPerKg;
        }

        // Hitung pembagian berdasarkan opsi pembayaran
        $paymentOption = $request->payment_option;
        $donationAmount = 0;
        $nasabahAmount = 0;

        if ($paymentOption === 'take_all') {
            $nasabahAmount = $totalHarga;
            $donationAmount = 0;
        } elseif ($paymentOption === 'donate_all') {
            $nasabahAmount = 0;
            $donationAmount = $totalHarga;
        } elseif ($paymentOption === 'donate_partial') {
            $donationAmount = (float) ($request->donation_amount ?? 0);
            $nasabahAmount = $totalHarga - $donationAmount;

            // Validasi donasi tidak boleh lebih dari total harga
            if ($donationAmount > $totalHarga) {
                return back()->with('error', 'Jumlah donasi tidak boleh melebihi total harga!');
            }
        }

        // Update penjemputan dengan opsi pembayaran
        $penjemputan->update([
            'payment_option' => $paymentOption,
            'donation_amount' => $donationAmount,
            'nasabah_amount' => $nasabahAmount,
        ]);

        \Log::info('[prosesPembayaran] Payment options saved:', [
            'penjemputan_id' => $penjemputan->id,
            'payment_option' => $paymentOption,
            'donation_amount' => $donationAmount,
            'nasabah_amount' => $nasabahAmount
        ]);

        // Jika donate_all, langsung proses tanpa Midtrans
        if ($paymentOption === 'donate_all') {
            \Log::info('[prosesPembayaran] Processing donate_all directly without Midtrans');

            // Update status langsung ke completed
            $penjemputan->update([
                'payment_status' => 'paid',
                'payment_time' => now(),
                'status' => 'completed'
            ]);

            // Buat transaksi sistem langsung
            $this->createTransaksiSistem($penjemputan, $donationAmount, $totalHarga);

            \Log::info('[prosesPembayaran] donate_all processed successfully', [
                'penjemputan_id' => $penjemputan->id,
                'donation_amount' => $donationAmount
            ]);

            return redirect()->route('filament.admin.pages.dashboard-pengepul')
                ->with('success', 'Sampah berhasil disumbangkan ke sistem! Tidak perlu pembayaran karena semua untuk sedekah.');
        }

        // Untuk take_all dan donate_partial, tetap ke Midtrans
        $penjemputan->update([
            'status' => 'weight_verified' // Tetap di weight_verified sambil menunggu Midtrans
        ]);

        return redirect()->route('filament.admin.pages.dashboard-pengepul')
            ->with('success', 'Opsi pembayaran berhasil disimpan! Silakan lanjutkan ke Midtrans.');
    }

    /**
     * Payment success callback
     */
    public function paymentSuccess(Penjemputan $penjemputan)
    {
        // Update payment status
        $penjemputan->update([
            'payment_status' => 'paid',
            'payment_time' => now(),
            'status' => 'completed'
        ]);

        // Gunakan opsi pembayaran yang sudah disimpan
        $paymentOption = $penjemputan->payment_option ?? 'take_all';
        $donationAmount = $penjemputan->donation_amount ?? 0;
        $nasabahAmount = $penjemputan->nasabah_amount ?? 0;

        \Log::info('[paymentSuccess] Using payment options:', [
            'payment_option' => $paymentOption,
            'donation_amount' => $donationAmount,
            'nasabah_amount' => $nasabahAmount,
            'penjemputan_id' => $penjemputan->id
        ]);

        // Hitung total harga untuk proporsi
        $totalHarga = 0;
        foreach ($penjemputan->sampahDetails as $detail) {
            $hargaPerKg = (float) ($detail->jenisSampah->harga ?? 0);
            $totalHarga += (float) $detail->berat_verifikasi * $hargaPerKg;
        }

        $ketuaKelompok = $penjemputan->kelompok->nasabah->first();

        // Buat transaksi berdasarkan skenario pembayaran
        if ($paymentOption === 'take_all') {
            // Skenario 1: 100% ke Nasabah
            $this->createTransaksiNasabah($penjemputan, $ketuaKelompok, $nasabahAmount, $totalHarga);

        } elseif ($paymentOption === 'donate_all') {
            // Skenario 2: 100% ke Sistem
            $this->createTransaksiSistem($penjemputan, $donationAmount, $totalHarga);

        } elseif ($paymentOption === 'donate_partial') {
            // Skenario 3: Split (Nasabah + Sistem)
            if ($nasabahAmount > 0) {
                $this->createTransaksiNasabah($penjemputan, $ketuaKelompok, $nasabahAmount, $totalHarga);
            }
            if ($donationAmount > 0) {
                $this->createTransaksiSistem($penjemputan, $donationAmount, $totalHarga);
            }
        }

        // Update saldo ketua kelompok jika ada amount untuk nasabah
        if ($nasabahAmount > 0 && $ketuaKelompok) {
            $ketuaKelompok->increment('saldo', $nasabahAmount);
        }

        return redirect()->route('filament.admin.pages.dashboard-pengepul')
            ->with('success', 'Pembayaran berhasil! Penjemputan selesai.');
    }

    /**
     * Payment pending callback
     */
    public function paymentPending(Penjemputan $penjemputan)
    {
        // Update payment status
        $penjemputan->update([
            'payment_status' => 'pending'
        ]);

        return redirect()->route('filament.admin.pages.dashboard-pengepul')
            ->with('warning', 'Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
    }

    /**
     * Payment error callback
     */
    public function paymentError(Penjemputan $penjemputan)
    {
        // Update payment status
        $penjemputan->update([
            'payment_status' => 'failed'
        ]);

        return redirect()->route('filament.admin.pages.dashboard-pengepul')
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    /**
     * Handle Midtrans webhook
     */
    public function handleWebhook(Request $request)
    {
        $midtransService = new MidtransService();
        return $midtransService->handleWebhook($request);
    }

    /**
     * Buat transaksi untuk nasabah
     */
    private function createTransaksiNasabah($penjemputan, $ketuaKelompok, $nasabahAmount, $totalHarga)
    {
        if (!$ketuaKelompok)
            return;

        foreach ($penjemputan->sampahDetails as $detail) {
            $hargaPerKgDetail = (float) ($detail->jenisSampah->harga ?? 0);
            $totalHargaItem = $hargaPerKgDetail * (float) $detail->berat_verifikasi;

            // Hitung proporsi untuk nasabah
            $nasabahItemAmount = ($totalHargaItem / $totalHarga) * $nasabahAmount;

            Transaksi::create([
                'penjemputan_id' => $penjemputan->id,
                'nasabah_id' => $ketuaKelompok->id,
                'pengepul_id' => Auth::id(),
                'jenis_sampah_id' => $detail->jenis_sampah_id,
                'berat' => (float) $detail->berat_verifikasi,
                // harga_per_kg tidak perlu disimpan, bisa diambil dari relasi jenisSampah->harga
                'total_harga' => $nasabahItemAmount,
                'tanggal_transaksi' => now(),
                'catatan' => 'Pembayaran penjemputan sampah via Midtrans ke ketua kelompok',
                'sistem' => false,
                'nasabah' => true,
                'gambar_bukti_nasabah' => $this->generateBuktiTransaksi('nasabah', $penjemputan->id),
                'gambar_bukti_sistem' => null,
            ]);
        }
    }

    /**
     * Buat transaksi untuk sistem (sedekah)
     */
    private function createTransaksiSistem($penjemputan, $donationAmount, $totalHarga)
    {
        foreach ($penjemputan->sampahDetails as $detail) {
            $hargaPerKgDetail = (float) ($detail->jenisSampah->harga ?? 0);
            $totalHargaItem = $hargaPerKgDetail * (float) $detail->berat_verifikasi;

            // Hitung proporsi untuk sistem
            $sistemItemAmount = ($totalHargaItem / $totalHarga) * $donationAmount;

            Transaksi::create([
                'penjemputan_id' => $penjemputan->id,
                'nasabah_id' => $penjemputan->kelompok->nasabah->first()->id ?? null,
                'pengepul_id' => Auth::id(),
                'jenis_sampah_id' => $detail->jenis_sampah_id,
                'berat' => (float) $detail->berat_verifikasi,
                // harga_per_kg tidak perlu disimpan, bisa diambil dari relasi jenisSampah->harga
                'total_harga' => $sistemItemAmount,
                'tanggal_transaksi' => now(),
                'catatan' => 'Sedekah sampah ke sistem via Midtrans',
                'sistem' => true,
                'nasabah' => false,
                'gambar_bukti_nasabah' => null,
                'gambar_bukti_sistem' => $this->generateBuktiTransaksi('sistem', $penjemputan->id),
            ]);
        }
    }

    /**
     * Generate bukti transaksi
     */
    private function generateBuktiTransaksi($type, $penjemputanId)
    {
        // Generate bukti transaksi berdasarkan data Midtrans
        $penjemputan = \App\Models\Penjemputan::find($penjemputanId);

        if (!$penjemputan || !$penjemputan->midtrans_order_id) {
            return "bukti_{$type}_penjemputan_{$penjemputanId}_" . time() . ".jpg";
        }

        // Generate bukti transaksi dengan format yang lebih informatif
        $timestamp = now()->format('Y-m-d_H-i-s');
        $orderId = $penjemputan->midtrans_order_id;

        return "bukti_{$type}_penjemputan_{$penjemputanId}_{$orderId}_{$timestamp}.jpg";
    }
}

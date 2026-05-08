<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penjemputan;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class SyncPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payment status from Midtrans for pending payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = 10; // Default limit
        
        $this->info("🔄 Syncing payment status from Midtrans...");
        
        // Get pending payments
        $pendingPayments = Penjemputan::where('payment_status', 'pending')
            ->whereNotNull('midtrans_order_id')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
            
        $this->info("Found {$pendingPayments->count()} pending payments to check");
        
        $midtransService = new MidtransService();
        $syncedCount = 0;
        
        foreach ($pendingPayments as $payment) {
            $this->line("Checking Order: {$payment->midtrans_order_id}");
            
            try {
                $midtransStatus = $midtransService->checkTransactionStatus($payment->midtrans_order_id);
                
                if ($midtransStatus) {
                    $midtransTransactionStatus = $midtransStatus->transaction_status ?? 'unknown';
                    
                    $this->line("  Midtrans Status: {$midtransTransactionStatus}");
                    
                    // If Midtrans says 'settlement' but DB says 'pending', update it
                    if ($midtransTransactionStatus === 'settlement') {
                        $this->line("  🔄 Updating status to 'paid'...");
                        
                        $payment->update([
                            'payment_status' => 'paid',
                            'payment_method' => $midtransStatus->payment_type ?? 'qris',
                            'payment_time' => now(),
                            'status' => 'completed'
                        ]);
                        
                        // Create transactions
                        $this->createTransactionsForPayment($payment);
                        
                        $this->info("  ✅ Status updated to 'paid'");
                        $syncedCount++;
                        
                    } elseif ($midtransTransactionStatus === 'expire') {
                        $this->line("  ⚠️ Transaction expired");
                    } else {
                        $this->line("  ℹ️ Status: {$midtransTransactionStatus}");
                    }
                } else {
                    $this->error("  ❌ Cannot check Midtrans status");
                }
                
            } catch (\Exception $e) {
                $this->error("  ❌ Error: " . $e->getMessage());
            }
        }
        
        $this->info("🎉 Synced {$syncedCount} payment(s)!");
        
        return Command::SUCCESS;
    }
    
    /**
     * Create transactions for completed payment
     */
    private function createTransactionsForPayment($penjemputan)
    {
        try {
            $paymentOption = $penjemputan->payment_option ?? 'take_all';
            $donationAmount = $penjemputan->donation_amount ?? 0;
            $nasabahAmount = $penjemputan->nasabah_amount ?? 0;
            
            // Calculate total harga
            $totalHarga = 0;
            foreach($penjemputan->sampahDetails as $detail) {
                $hargaPerKg = (float)($detail->jenisSampah->harga ?? 0);
                $totalHarga += (float)$detail->berat_verifikasi * $hargaPerKg;
            }
            
            $ketuaKelompok = $penjemputan->kelompok->nasabah->first();
            
            // Create transactions based on payment option (same logic as controller)
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
            
            // Update nasabah saldo if needed
            if ($nasabahAmount > 0 && $ketuaKelompok) {
                $ketuaKelompok->increment('saldo', $nasabahAmount);
                $this->line("    💰 Nasabah saldo updated: +Rp " . number_format($nasabahAmount, 0, ',', '.'));
            }
            
            Log::info('Payment status synced automatically', [
                'penjemputan_id' => $penjemputan->id,
                'order_id' => $penjemputan->midtrans_order_id,
                'payment_option' => $paymentOption,
                'nasabah_amount' => $nasabahAmount,
                'donation_amount' => $donationAmount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create transactions for synced payment', [
                'penjemputan_id' => $penjemputan->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Create nasabah transactions (copied from controller)
     */
    private function createTransaksiNasabah($penjemputan, $ketuaKelompok, $nasabahAmount, $totalHarga)
    {
        if (!$ketuaKelompok) return;

        foreach($penjemputan->sampahDetails as $detail) {
            $hargaPerKgDetail = (float)($detail->jenisSampah->harga ?? 0);
            $totalHargaItem = $hargaPerKgDetail * (float)$detail->berat_verifikasi;
            
            // Hitung proporsi untuk nasabah
            $nasabahItemAmount = ($totalHargaItem / $totalHarga) * $nasabahAmount;
            
            \App\Models\Transaksi::create([
                'penjemputan_id' => $penjemputan->id,
                'nasabah_id' => $ketuaKelompok->id,
                'pengepul_id' => $penjemputan->pengepul_id,
                'jenis_sampah_id' => $detail->jenis_sampah_id,
                'berat' => (float)$detail->berat_verifikasi,
                // harga_per_kg tidak perlu disimpan, bisa diambil dari relasi jenisSampah->harga
                'total_harga' => $nasabahItemAmount,
                'tanggal_transaksi' => now(),
                'catatan' => 'Pembayaran penjemputan sampah via Midtrans ke ketua kelompok',
                'sistem' => false,
                'nasabah' => true,
            ]);
        }
    }
    
    /**
     * Create sistem transactions (copied from controller)
     */
    private function createTransaksiSistem($penjemputan, $donationAmount, $totalHarga)
    {
        foreach($penjemputan->sampahDetails as $detail) {
            $hargaPerKgDetail = (float)($detail->jenisSampah->harga ?? 0);
            $totalHargaItem = $hargaPerKgDetail * (float)$detail->berat_verifikasi;
            
            // Hitung proporsi untuk sistem
            $sistemItemAmount = ($totalHargaItem / $totalHarga) * $donationAmount;
            
            \App\Models\Transaksi::create([
                'penjemputan_id' => $penjemputan->id,
                'nasabah_id' => $penjemputan->kelompok->nasabah->first()->id ?? null,
                'pengepul_id' => $penjemputan->pengepul_id,
                'jenis_sampah_id' => $detail->jenis_sampah_id,
                'berat' => (float)$detail->berat_verifikasi,
                // harga_per_kg tidak perlu disimpan, bisa diambil dari relasi jenisSampah->harga
                'total_harga' => $sistemItemAmount,
                'tanggal_transaksi' => now(),
                'catatan' => 'Sedekah sampah ke sistem via Midtrans',
                'sistem' => true,
                'nasabah' => false,
            ]);
        }
    }
}

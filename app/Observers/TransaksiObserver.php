<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Models\Kas;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Helpers\CacheHelper;

class TransaksiObserver
{
    public function created(Transaksi $transaksi): void
    {
        try {
            // HAPUS: Update saldo nasabah di observer karena sudah dihandle di controller
            // Saldo nasabah diupdate di PengepulController setelah semua transaksi dibuat

            // Log aktivitas berdasarkan tipe transaksi
            $activityType = $transaksi->sistem ? 'create_transaksi_sistem' : 'create_transaksi_nasabah';
            $nasabahName = $transaksi->nasabah ? $transaksi->nasabah->nama : 'Unknown';
            $jenisSampahName = $transaksi->jenisSampah ? $transaksi->jenisSampah->nama : 'Unknown';
            
            $description = $transaksi->sistem 
                ? "Membuat transaksi sedekah ke sistem - {$jenisSampahName} ({$transaksi->berat}kg) = {$transaksi->formatted_total_harga}"
                : "Membuat transaksi ke nasabah - {$nasabahName} - {$jenisSampahName} ({$transaksi->berat}kg) = {$transaksi->formatted_total_harga}";

            LogAktivitas::create([
                'user_id' => $transaksi->pengepul_id,
                'activity' => $activityType,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Notifikasi untuk nasabah jika transaksi untuk nasabah
            // Load relasi jika belum di-load
            if (!$transaksi->relationLoaded('nasabah')) {
                $transaksi->load('nasabah');
            }
            
            if ($transaksi->nasabah && $transaksi->nasabah->user_id) {
                $formattedHarga = $transaksi->formatted_total_harga ?? 'Rp 0';
                Notification::create([
                    'user_id' => $transaksi->nasabah->user_id,
                    'nasabah_id' => $transaksi->nasabah_id,
                    'type' => Notification::TYPE_TRANSAKSI,
                    'title' => 'Transaksi Baru',
                    'message' => "Transaksi baru: {$jenisSampahName} ({$transaksi->berat}kg) = {$formattedHarga}",
                    'data' => [
                        'transaksi_id' => $transaksi->id,
                        'penjemputan_id' => $transaksi->penjemputan_id,
                    ],
                ]);
            }

            // Clear cache terkait transaksi
            CacheHelper::clearTransaksiCache();
        } catch (\Exception $e) {
            // Log error but don't break the transaction creation
            \Log::error('TransaksiObserver error: ' . $e->getMessage());
        }
    }
}
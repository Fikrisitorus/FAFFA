<?php

namespace App\Observers;

use App\Models\SedekahSampah;
use App\Models\Kas;
use App\Models\LogAktivitas;

class SedekahSampahObserver
{
    public function created(SedekahSampah $sedekahSampah): void
    {
        // Kurangi saldo nasabah
        $nasabah = $sedekahSampah->nasabah;
        
        if ($nasabah->saldo >= $sedekahSampah->jumlah) {
            $nasabah->saldo -= $sedekahSampah->jumlah;
            $nasabah->save();

            // Tambah ke kas bank sampah
            $saldoSebelum = Kas::getCurrentSaldo();
            $saldoSesudah = $saldoSebelum + $sedekahSampah->jumlah;

            Kas::create([
                'nasabah_id' => $nasabah->id,
                'sedekah_sampah_id' => $sedekahSampah->id,
                'tipe' => Kas::TIPE_MASUK,
                'jumlah' => $sedekahSampah->jumlah,
                'deskripsi' => "Sedekah sampah dari {$nasabah->nama}",
                'tanggal' => $sedekahSampah->tanggal_sedekah,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
            ]);

            // Mark sebagai processed
            $sedekahSampah->is_processed = true;
            $sedekahSampah->save();

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'activity' => 'sedekah_sampah',
                'description' => "{$nasabah->nama} berседекah sampah sebesar {$sedekahSampah->formatted_jumlah}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
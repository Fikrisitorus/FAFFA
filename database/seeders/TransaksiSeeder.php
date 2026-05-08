<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\Penjemputan;
use App\Models\User;
use App\Models\JenisSampah;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $penjemputan = Penjemputan::first();
        $pengepul = User::role('pengepul')->first();
        $nasabah = $penjemputan?->nasabah;
        $jenisSampah = JenisSampah::first();

        if ($penjemputan && $pengepul && $nasabah && $jenisSampah) {
            Transaksi::create([
                'nasabah_id' => $nasabah->id,
                'pengepul_id' => $pengepul->id,
                'penjemputan_id' => $penjemputan->id,
                'jenis_sampah_id' => $jenisSampah->id,
                'berat' => 12.5,
                'harga_per_kg' => 2500,
                'total_harga' => 12.5 * 2500,
                'tanggal_transaksi' => Carbon::now(),
                'catatan' => 'Setoran plastik dan kertas',
            ]);
        }
    }
} 
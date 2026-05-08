<?php

namespace Database\Seeders;

use App\Models\HargaSampah;
use App\Models\JenisSampah;
use Illuminate\Database\Seeder;

class HargaSampahSeeder extends Seeder
{
    public function run(): void
    {
        $hargaSampah = [
            ['jenis_sampah_id' => 1, 'harga' => 3000, 'tanggal_berlaku' => now()],
            ['jenis_sampah_id' => 2, 'harga' => 1500, 'tanggal_berlaku' => now()],
            ['jenis_sampah_id' => 3, 'harga' => 2000, 'tanggal_berlaku' => now()],
            ['jenis_sampah_id' => 4, 'harga' => 5000, 'tanggal_berlaku' => now()],
            ['jenis_sampah_id' => 5, 'harga' => 2500, 'tanggal_berlaku' => now()],
        ];

        foreach ($hargaSampah as $item) {
            HargaSampah::create($item);
        }
    }
}
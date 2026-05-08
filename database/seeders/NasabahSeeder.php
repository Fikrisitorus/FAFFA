<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\Kelompok;
use Illuminate\Database\Seeder;

class NasabahSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kelompok yang sudah disediakan oleh KelompokSeeder (jika ada)
        $kelompokA = Kelompok::where('kode', 'KLP-A')->first();
        $kelompokB = Kelompok::where('kode', 'KLP-B')->first();

        $nasabahData = [
            [
                'kode_nasabah'      => 'NSB0001',
                'nama'              => 'Budi Santoso',
                'email'             => 'budi@email.com',
                'phone'             => '081234567890',
                'address'           => 'Jl. Mawar No. 1, Yogyakarta',
                'kelompok_id'       => optional($kelompokA)->id, // atau null jika tidak ada
                'tanggal_bergabung' => now()->subMonths(6),
                'saldo'             => 0,
            ],
            [
                'kode_nasabah'      => 'NSB0002',
                'nama'              => 'Siti Aminah',
                'email'             => 'siti@email.com',
                'phone'             => '081234567891',
                'address'           => 'Jl. Melati No. 2, Yogyakarta',
                'kelompok_id'       => optional($kelompokA)->id,
                'tanggal_bergabung' => now()->subMonths(4),
                'saldo'             => 0,
            ],
            [
                'kode_nasabah'      => 'NSB0003',
                'nama'              => 'Agus Wijaya',
                'email'             => 'agus@email.com',
                'phone'             => '081234567892',
                'address'           => 'Jl. Kenanga No. 3, Yogyakarta',
                'kelompok_id'       => optional($kelompokB)->id,
                'tanggal_bergabung' => now()->subMonths(3),
                'saldo'             => 0,
            ],
            // Contoh nasabah tanpa kelompok
            [
                'kode_nasabah'      => 'NSB0004',
                'nama'              => 'Tanpa Kelompok 1',
                'email'             => 'tanpakel1@email.com',
                'phone'             => '081200000001',
                'address'           => 'Jl. Bebas No. 4, Yogyakarta',
                'kelompok_id'       => null,
                'tanggal_bergabung' => now()->subMonths(2),
                'saldo'             => 0,
            ],
            [
                'kode_nasabah'      => 'NSB0005',
                'nama'              => 'Tanpa Kelompok 2',
                'email'             => 'tanpakel2@email.com',
                'phone'             => '081200000002',
                'address'           => 'Jl. Bebas No. 5, Yogyakarta',
                'kelompok_id'       => null,
                'tanggal_bergabung' => now()->subMonth(),
                'saldo'             => 0,
            ],
        ];

        foreach ($nasabahData as $data) {
            Nasabah::updateOrCreate(
                ['kode_nasabah' => $data['kode_nasabah']], // unik
                $data
            );
        }
    }
}

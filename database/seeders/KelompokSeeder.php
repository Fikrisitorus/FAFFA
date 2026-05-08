<?php

namespace Database\Seeders;

use App\Models\Kelompok;
use Illuminate\Database\Seeder;

class KelompokSeeder extends Seeder
{
    public function run(): void
    {
        $kelompok = [
            [
                'nama' => 'Kelompok A - Malioboro',
                'kode' => 'KLP-A',
                'deskripsi' => 'Kelompok nasabah area Malioboro',
                'koordinator' => 'Budi Santoso',
                'lokasi' => 'Jl. Malioboro, Yogyakarta',
                'jadwal_rutin' => [
                    ['hari' => 'Senin', 'jam' => '08:00-16:00'],
                    ['hari' => 'Kamis', 'jam' => '08:00-16:00'],
                ],
            ],
            [
                'nama' => 'Kelompok B - Prawirotaman',
                'kode' => 'KLP-B',
                'deskripsi' => 'Kelompok nasabah area Prawirotaman',
                'koordinator' => 'Siti Aminah',
                'lokasi' => 'Jl. Prawirotaman, Yogyakarta',
                'jadwal_rutin' => [
                    ['hari' => 'Selasa', 'jam' => '08:00-16:00'],
                    ['hari' => 'Jumat', 'jam' => '08:00-16:00'],
                ],
            ],
            [
                'nama' => 'Kelompok C - Taman Siswa',
                'kode' => 'KLP-C',
                'deskripsi' => 'Kelompok nasabah area Taman Siswa',
                'koordinator' => 'Ahmad Wijaya',
                'lokasi' => 'Jl. Taman Siswa, Yogyakarta',
                'jadwal_rutin' => [
                    ['hari' => 'Rabu', 'jam' => '08:00-16:00'],
                    ['hari' => 'Sabtu', 'jam' => '08:00-16:00'],
                ],
            ],
        ];

        foreach ($kelompok as $item) {
            Kelompok::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'deskripsi' => $item['deskripsi'],
                    'koordinator' => $item['koordinator'],
                    'lokasi' => $item['lokasi'],
                    'jadwal_rutin' => $item['jadwal_rutin'], // cukup array, Laravel akan simpan JSON
                    'is_active' => true,
                ]
            );
        }
    }
}

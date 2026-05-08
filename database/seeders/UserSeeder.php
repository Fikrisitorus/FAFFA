<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@banksampah.com'], // cari by email
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
            ]
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Pengepul
        $pengepul = User::firstOrCreate(
            [
                'email' => 'pengepul1@banksampah.com',
            ],
            [
                'name' => 'Pengepul Sampah 1',
                'password' => bcrypt('password'),
                'phone' => '08123456789',
                'address' => 'Jl. Pengepul No. 1',
                'is_active' => true,
                'is_verified' => true,
            ]
        );
        if (! $pengepul->hasRole('pengepul')) {
            $pengepul->assignRole('pengepul');
        }

        // Kelompok Nasabah - Malioboro
        $kelompokMalioboro = \App\Models\Kelompok::where('kode', 'KLP-A')->first();
        if ($kelompokMalioboro) {
            $kelompokNasabahMalioboro = User::firstOrCreate(
                [
                    'email' => 'kelompok.malioboro@banksampah.com',
                ],
                [
                    'name' => 'Ketua Kelompok Malioboro',
                    'password' => bcrypt('password'),
                    'phone' => '08129876543',
                    'address' => 'Jl. Malioboro, Yogyakarta',
                    'is_active' => true,
                    'is_verified' => true,
                    'kelompok_id' => $kelompokMalioboro->id,
                ]
            );
            if (! $kelompokNasabahMalioboro->hasRole('kelompok_nasabah')) {
                $kelompokNasabahMalioboro->assignRole('kelompok_nasabah');
            }
        }

        // Kelompok Nasabah - Prawirotaman
        $kelompokPrawirotaman = \App\Models\Kelompok::where('kode', 'KLP-B')->first();
        if ($kelompokPrawirotaman) {
            $kelompokNasabahPrawirotaman = User::firstOrCreate(
                [
                    'email' => 'kelompok.prawirotaman@banksampah.com',
                ],
                [
                    'name' => 'Ketua Kelompok Prawirotaman',
                    'password' => bcrypt('password'),
                    'phone' => '08129876544',
                    'address' => 'Jl. Prawirotaman, Yogyakarta',
                    'is_active' => true,
                    'is_verified' => true,
                    'kelompok_id' => $kelompokPrawirotaman->id,
                ]
            );
            if (! $kelompokNasabahPrawirotaman->hasRole('kelompok_nasabah')) {
                $kelompokNasabahPrawirotaman->assignRole('kelompok_nasabah');
            }
        }

        // Kelompok Nasabah - Taman Siswa
        $kelompokTamanSiswa = \App\Models\Kelompok::where('kode', 'KLP-C')->first();
        if ($kelompokTamanSiswa) {
            $kelompokNasabahTamanSiswa = User::firstOrCreate(
                [
                    'email' => 'kelompok.tamansiswa@banksampah.com',
                ],
                [
                    'name' => 'Ketua Kelompok Taman Siswa',
                    'password' => bcrypt('password'),
                    'phone' => '08129876545',
                    'address' => 'Jl. Taman Siswa, Yogyakarta',
                    'is_active' => true,
                    'is_verified' => true,
                    'kelompok_id' => $kelompokTamanSiswa->id,
                ]
            );
            if (! $kelompokNasabahTamanSiswa->hasRole('kelompok_nasabah')) {
                $kelompokNasabahTamanSiswa->assignRole('kelompok_nasabah');
            }
        }

    }
 }

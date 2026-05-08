<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelompok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KelompokNasabahSeeder extends Seeder
{
    public function run(): void
    {
        // Buat beberapa kelompok
        $kelompoks = [
            [
                'nama' => 'Kelompok Sampah Hijau',
                'deskripsi' => 'Kelompok pengelola sampah organik',
                'lokasi' => 'Yogyakarta',
                'is_active' => true,
            ],
            [
                'nama' => 'Kelompok Sampah Bersih',
                'deskripsi' => 'Kelompok pengelola sampah anorganik',
                'lokasi' => 'Sleman',
                'is_active' => true,
            ],
            [
                'nama' => 'Kelompok Sampah Mandiri',
                'deskripsi' => 'Kelompok pengelola sampah terpadu',
                'lokasi' => 'Bantul',
                'is_active' => true,
            ],
        ];

        foreach ($kelompoks as $index => $kelompokData) {
            $kelompok = Kelompok::create($kelompokData);
            
            // Buat user untuk setiap kelompok
            $user = User::create([
                'name' => 'Ketua ' . $kelompokData['nama'],
                'email' => 'kelompok' . ($index + 1) . '@banksampah.com',
                'password' => Hash::make('password'),
                'phone' => '0812345678' . ($index + 1),
                'address' => $kelompokData['lokasi'],
                'is_active' => true,
                'is_verified' => true,
                'kelompok_id' => $kelompok->id,
            ]);
            
            $user->assignRole('kelompok_nasabah');
            
            echo "Created user: {$user->name} ({$user->email}) with password: password\n";
        }
    }
} 
<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Kelompok;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    protected $signature = 'create:test-user';
    protected $description = 'Create test users for different roles';

    public function handle()
    {
        $this->info('Creating test users...');

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@banksampah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'is_active' => true,
                'is_verified' => true,
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        $this->info('Admin created: admin@banksampah.com / password');

        // Create Pengepul
        $pengepul = User::firstOrCreate(
            ['email' => 'pengepul@banksampah.com'],
            [
                'name' => 'Pengepul Sampah',
                'password' => Hash::make('password'),
                'phone' => '08123456789',
                'address' => 'Jl. Pengepul No. 1',
                'is_active' => true,
                'is_verified' => true,
            ]
        );
        if (!$pengepul->hasRole('pengepul')) {
            $pengepul->assignRole('pengepul');
        }
        $this->info('Pengepul created: pengepul@banksampah.com / password');

        // Create Kelompok Nasabah - Malioboro
        $kelompokMalioboro = Kelompok::where('kode', 'KLP-A')->first();
        if ($kelompokMalioboro) {
            $kelompokUser = User::firstOrCreate(
                ['email' => 'kelompok@banksampah.com'],
                [
                    'name' => 'Ketua Kelompok Malioboro',
                    'password' => Hash::make('password'),
                    'phone' => '08129876543',
                    'address' => 'Jl. Malioboro, Yogyakarta',
                    'is_active' => true,
                    'is_verified' => true,
                    'kelompok_id' => $kelompokMalioboro->id,
                ]
            );
            if (!$kelompokUser->hasRole('kelompok_nasabah')) {
                $kelompokUser->assignRole('kelompok_nasabah');
            }
            $this->info('Kelompok user created: kelompok@banksampah.com / password');
            $this->info('Connected to group: ' . $kelompokMalioboro->nama . ' (' . $kelompokMalioboro->lokasi . ')');
        }

        $this->info('All test users created successfully!');
        $this->info('');
        $this->info('Login credentials:');
        $this->info('Admin: admin@banksampah.com / password');
        $this->info('Pengepul: pengepul@banksampah.com / password');
        $this->info('Kelompok: kelompok@banksampah.com / password');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar role yang akan dibuat
        $roles = ['admin', 'pengepul', 'kelompok_nasabah', 'nasabah'];

        // Buat semua role jika belum ada
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('admin');
        
        // Daftar user yang akan dibuat
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Pengepul Utama',
                'email' => 'pengepul@example.com',
                'role' => 'pengepul',
            ],
            [
                'name' => 'Kelompok Nasabah Utama',
                'email' => 'kelompoknasabah@example.com',
                'role' => 'kelompok_nasabah',
            ],
        ];

        // Loop user
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Beri role jika belum punya
            if (! $user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }
    }
}

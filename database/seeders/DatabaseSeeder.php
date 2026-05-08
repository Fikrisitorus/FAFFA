<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            KelompokSeeder::class,
            JenisSampahSeeder::class,
            HargaSampahSeeder::class,
            NasabahSeeder::class,
            UserRoleSeeder::class,
            SettingSeeder::class,
            ArtikelSeeder::class,
        ]);
    }
}
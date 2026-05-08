<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $permissions = [
        'view users',
        'create users',
        'edit users',
        'delete users',
        'view nasabah',
        'create nasabah',
        'edit nasabah',
        'delete nasabah',
        'view transaksi',
        'create transaksi',
        'edit transaksi',
        'delete transaksi',
        'view penjemputan',
        'create penjemputan',
        'edit penjemputan',
        'delete penjemputan',
        'view artikel',
        'create artikel',
        'edit artikel',
        'delete artikel',
        'view laporan',
        'view kas',
        'edit kas',
        'view log',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]);
    }

    $admin = Role::firstOrCreate(['name' => 'admin']);
    $pengepul = Role::firstOrCreate(['name' => 'pengepul']);
    $kelompokNasabah = Role::firstOrCreate(['name' => 'kelompok_nasabah']);
    $nasabah = Role::firstOrCreate(['name' => 'nasabah']);

    $admin->syncPermissions([
        'view users',
        'view nasabah',
        'view transaksi',
        'view penjemputan',
        'view artikel',
        'view laporan',
        'view kas',
        'view log',
    ]);
    $pengepul->syncPermissions([
        'view penjemputan',
        'edit penjemputan',
        'view transaksi',
        'create transaksi',
        'edit transaksi',
    ]);
    $kelompokNasabah->syncPermissions([
        'view nasabah',
        'view transaksi',
        'view penjemputan',
        'view artikel',
    ]);
    
    $nasabah->syncPermissions([
        'view transaksi',
        'view penjemputan',
        'view artikel',
    ]);
}

}
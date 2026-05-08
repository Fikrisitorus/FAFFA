<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PenggunaanDana;
use App\Models\User;
use Carbon\Carbon;

class PenggunaanDanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $admin = User::first();
        }

        $penggunaanDana = [
            [
                'tanggal_penggunaan' => Carbon::now()->subDays(5),
                'kategori' => 'operasional',
                'deskripsi' => 'Bensin untuk kendaraan penjemputan sampah',
                'jumlah_pengeluaran' => 150000,
                'created_by' => $admin->id,
            ],
            [
                'tanggal_penggunaan' => Carbon::now()->subDays(3),
                'kategori' => 'maintenance',
                'deskripsi' => 'Perbaikan mesin press sampah',
                'jumlah_pengeluaran' => 500000,
                'created_by' => $admin->id,
            ],
            [
                'tanggal_penggunaan' => Carbon::now()->subDays(1),
                'kategori' => 'gaji',
                'deskripsi' => 'Gaji pengepul bulan ini',
                'jumlah_pengeluaran' => 2000000,
                'created_by' => $admin->id,
            ],
            [
                'tanggal_penggunaan' => Carbon::now()->subWeeks(2),
                'kategori' => 'infrastruktur',
                'deskripsi' => 'Pembelian tempat sampah baru',
                'jumlah_pengeluaran' => 750000,
                'created_by' => $admin->id,
            ],
            [
                'tanggal_penggunaan' => Carbon::now()->subWeeks(1),
                'kategori' => 'operasional',
                'deskripsi' => 'Biaya parkir dan tol',
                'jumlah_pengeluaran' => 75000,
                'created_by' => $admin->id,
            ],
            [
                'tanggal_penggunaan' => Carbon::now()->subMonth(),
                'kategori' => 'lainnya',
                'deskripsi' => 'Biaya administrasi dan legal',
                'jumlah_pengeluaran' => 300000,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($penggunaanDana as $data) {
            PenggunaanDana::create($data);
        }
    }
}

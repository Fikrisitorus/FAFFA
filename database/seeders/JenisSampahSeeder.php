<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use Illuminate\Database\Seeder;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        $jenisSampah = [
            ['nama' => 'Botol Plastik', 'kategori' => 'plastik', 'satuan' => 'kg', 'deskripsi' => 'Botol plastik bekas minuman', 'harga' => 3000],
            ['nama' => 'Kertas Koran', 'kategori' => 'kertas', 'satuan' => 'kg', 'deskripsi' => 'Koran bekas', 'harga' => 1500],
            ['nama' => 'Kertas HVS', 'kategori' => 'kertas', 'satuan' => 'kg', 'deskripsi' => 'Kertas HVS bekas', 'harga' => 2000],
            ['nama' => 'Kaleng Aluminium', 'kategori' => 'logam', 'satuan' => 'kg', 'deskripsi' => 'Kaleng minuman bekas', 'harga' => 5000],
            ['nama' => 'Botol Kaca', 'kategori' => 'kaca', 'satuan' => 'kg', 'deskripsi' => 'Botol kaca bekas', 'harga' => 2500],
        ];

        foreach ($jenisSampah as $item) {
            // Cek apakah sudah ada, jika belum baru create
            if (!JenisSampah::where('nama', $item['nama'])->exists()) {
                JenisSampah::create($item);
            }
        }
    }
}
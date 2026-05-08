<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjemputan;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Nasabah;
use Carbon\Carbon;

class TestPenjemputanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data yang diperlukan
        $kelompok = Kelompok::first();
        $nasabahUser = User::whereHas('roles', function($q){ $q->where('name', 'kelompok_nasabah'); })->first();
        
        // Pastikan ada data nasabah
        $nasabah = $nasabahUser?->nasabah;
        if (!$nasabah && $nasabahUser && $kelompok) {
            $nasabah = Nasabah::create([
                'user_id' => $nasabahUser->id,
                'kelompok_id' => $kelompok->id,
                'kode_nasabah' => 'NSB001',
                'nama' => $nasabahUser->name,
                'email' => $nasabahUser->email,
                'phone' => $nasabahUser->phone ?? '08123456789',
                'address' => $nasabahUser->address ?? 'Jl. Test',
                'tanggal_bergabung' => now(),
                'saldo' => 0,
                'is_active' => true,
            ]);
        }

        if (!$nasabah || !$kelompok) {
            echo "Data nasabah atau kelompok tidak ditemukan!\n";
            return;
        }

        // Buat beberapa data penjemputan pending untuk testing
        $penjemputanData = [
            [
                'nasabah_id' => $nasabah->id,
                'kelompok_id' => $kelompok->id,
                'tanggal_penjemputan' => now()->addDays(1),
                'waktu_penjemputan' => now()->addDays(1)->setTime(10, 0),
                'jadwal_penjemputan' => now()->addDays(1)->setTime(10, 0),
                'alamat_penjemputan' => 'Jl. Malioboro No. 123, Yogyakarta',
                'catatan' => 'Sampah organik dan plastik',
                'status' => 'pending',
            ],
            [
                'nasabah_id' => $nasabah->id,
                'kelompok_id' => $kelompok->id,
                'tanggal_penjemputan' => now()->addDays(2),
                'waktu_penjemputan' => now()->addDays(2)->setTime(14, 30),
                'jadwal_penjemputan' => now()->addDays(2)->setTime(14, 30),
                'alamat_penjemputan' => 'Jl. Sudirman No. 45, Yogyakarta',
                'catatan' => 'Sampah kertas dan kardus',
                'status' => 'pending',
            ],
            [
                'nasabah_id' => $nasabah->id,
                'kelompok_id' => $kelompok->id,
                'tanggal_penjemputan' => now()->addDays(1),
                'waktu_penjemputan' => now()->addDays(1)->setTime(16, 0),
                'jadwal_penjemputan' => now()->addDays(1)->setTime(16, 0),
                'alamat_penjemputan' => 'Jl. Solo No. 67, Yogyakarta',
                'catatan' => 'Sampah campuran',
                'status' => 'pending',
            ],
        ];

        foreach ($penjemputanData as $data) {
            Penjemputan::firstOrCreate(
                [
                    'nasabah_id' => $data['nasabah_id'],
                    'kelompok_id' => $data['kelompok_id'],
                    'tanggal_penjemputan' => $data['tanggal_penjemputan'],
                    'waktu_penjemputan' => $data['waktu_penjemputan'],
                ],
                $data
            );
        }

        echo "Test data penjemputan berhasil dibuat!\n";
        echo "Total pending: " . Penjemputan::where('status', 'pending')->count() . "\n";
    }
}

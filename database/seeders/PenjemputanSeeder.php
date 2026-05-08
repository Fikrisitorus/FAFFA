<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjemputan;
use App\Models\User;
use App\Models\Kelompok;
use Carbon\Carbon;

class PenjemputanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pengepul dan kelompok nasabah yang benar-benar ada
        $pengepul = \App\Models\User::whereHas('roles', function($q){ $q->where('name', 'pengepul'); })->first();
        $kelompok = \App\Models\Kelompok::first();
        $nasabahUser = \App\Models\User::whereHas('roles', function($q){ $q->where('name', 'kelompok_nasabah'); })->first();
        // Pastikan user kelompok nasabah punya data Nasabah
        $nasabahModel = $nasabahUser?->nasabah;
        if (!$nasabahModel && $nasabahUser && $kelompok) {
            $nasabahModel = \App\Models\Nasabah::create([
                'user_id' => $nasabahUser->id,
                'kelompok_id' => $kelompok->id,
                'kode_nasabah' => 'NSB001',
                'nama' => $nasabahUser->name,
                'email' => $nasabahUser->email,
                'phone' => $nasabahUser->phone ?? '08123456789',
                'address' => $nasabahUser->address ?? 'Jl. Mawar',
                'tanggal_bergabung' => now(),
                'saldo' => 0,
                'is_active' => true,
            ]);
        }
        // Debug log
        info('Seeder Penjemputan: pengepul_id=' . ($pengepul?->id ?? 'null') . ', kelompok_id=' . ($kelompok?->id ?? 'null') . ', nasabah_id=' . ($nasabahModel?->id ?? 'null'));

        // Request pending (belum assigned pengepul)
        Penjemputan::create([
            'nasabah_id' => $nasabahModel?->id,
            'kelompok_id' => $kelompok?->id,
            'pengepul_id' => null,
            'tanggal_penjemputan' => Carbon::now()->addDay(),
            'waktu_penjemputan' => Carbon::now()->addDay()->setTime(10, 0),
            'alamat_penjemputan' => 'Jl. Mawar No. 1',
            'catatan' => 'Mohon jemput pagi',
            'status' => 'pending',
            'is_sorted' => true,
        ]);

        // Request pending (belum assigned pengepul)
        Penjemputan::create([
            'nasabah_id' => $nasabahModel?->id,
            'kelompok_id' => $kelompok?->id,
            'pengepul_id' => null,
            'tanggal_penjemputan' => Carbon::now()->addDays(2),
            'waktu_penjemputan' => Carbon::now()->addDays(2)->setTime(14, 0),
            'alamat_penjemputan' => 'Jl. Mawar No. 2',
            'catatan' => 'Sampah sudah dipilah',
            'status' => 'pending',
            'is_sorted' => false,
        ]);

        // Request assigned ke pengepul (on_progress)
        Penjemputan::create([
            'nasabah_id' => $nasabahModel?->id,
            'kelompok_id' => $kelompok?->id,
            'pengepul_id' => $pengepul?->id,
            'tanggal_penjemputan' => Carbon::now()->addDays(3),
            'waktu_penjemputan' => Carbon::now()->addDays(3)->setTime(9, 0),
            'alamat_penjemputan' => 'Jl. Mawar No. 3',
            'catatan' => 'Mohon konfirmasi sebelum jemput',
            'status' => 'on_progress',
            'is_sorted' => true,
        ]);
    }
} 
<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\Nasabah;
use App\Models\User;
use App\Models\JenisSampah;
use App\Models\Penjemputan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition(): array
    {
        $berat = fake()->randomFloat(2, 0.5, 50);
        $hargaPerKg = fake()->randomFloat(2, 1000, 5000);
        
        return [
            'nasabah_id' => Nasabah::factory(),
            'pengepul_id' => User::role('pengepul')->inRandomOrder()->first()?->id,
            'penjemputan_id' => Penjemputan::factory(),
            'jenis_sampah_id' => JenisSampah::inRandomOrder()->first()?->id ?? 1,
            'berat' => $berat,
            'harga_per_kg' => $hargaPerKg,
            'total_harga' => $berat * $hargaPerKg,
            'tanggal_transaksi' => fake()->dateTimeBetween('-6 months', 'now'),
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
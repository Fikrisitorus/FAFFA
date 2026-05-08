<?php

namespace Database\Factories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Factories\Factory;

class NasabahFactory extends Factory
{
    protected $model = Nasabah::class;

    public function definition(): array
    {
        return [
            'kode_nasabah' => 'NSB' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'kelompok' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'tanggal_bergabung' => fake()->dateTimeBetween('-2 years', 'now'),
            'saldo' => fake()->randomFloat(2, 0, 500000),
            'is_active' => true,
        ];
    }
}
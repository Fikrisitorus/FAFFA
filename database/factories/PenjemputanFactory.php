<?php

namespace Database\Factories;

use App\Models\Penjemputan;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenjemputanFactory extends Factory
{
    protected $model = Penjemputan::class;

    public function definition(): array
    {
        $tanggal = fake()->dateTimeBetween('-1 month', '+1 week');
        
        return [
            'nasabah_id' => Nasabah::factory(),
            'pengepul_id' => User::role('pengepul')->inRandomOrder()->first()?->id,
            'tanggal_penjemputan' => $tanggal->format('Y-m-d'),
            'waktu_penjemputan' => $tanggal,
            'alamat_penjemputan' => fake()->address(),
            'catatan' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'assigned', 'on_progress', 'completed']),
            'is_sorted' => fake()->boolean(70),
        ];
    }
}
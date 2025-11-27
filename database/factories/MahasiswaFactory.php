<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dosen;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dosenIds = Dosen::pluck('nidn')->toArray();

        return [
            'nim' => fake()->unique()->numerify('##########'), // 10 digit NIM
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(['aktif', 'non-aktif', 'lulus']),
            'dosen_pembimbing_id' => fake()->randomElement($dosenIds) ?? null,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'rumpun_ilmu' => fake()->randomElement(['fotografi', 'film dan televisi', 'animasi', 'produksi film dan televisi']),
        ];
    }
}

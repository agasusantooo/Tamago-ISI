<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester;
use Carbon\Carbon;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semester::create([
            'nama' => 'Gasal 2024/2025',
            'tanggal_mulai' => Carbon::parse('2024-09-01'),
            'tanggal_akhir' => Carbon::parse('2025-01-31'),
            'is_active' => true,
        ]);

        Semester::create([
            'nama' => 'Genap 2024/2025',
            'tanggal_mulai' => Carbon::parse('2025-02-01'),
            'tanggal_akhir' => Carbon::parse('2025-07-31'),
            'is_active' => false,
        ]);
    }
}

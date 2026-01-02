<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan RoleSeeder dulu
        $this->call([
            RoleSeeder::class,
            DemoUserSeeder::class,
            DosenSeeder::class,
            BimbinganSeeder::class,
            MahasiswaSeeder::class,
            SemesterSeeder::class,
        ]);
    }
}

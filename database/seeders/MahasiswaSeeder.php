<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Role;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role mahasiswa
        $roleMahasiswa = Role::where('name', 'mahasiswa')->first();

        if (!$roleMahasiswa) {
            $this->command->error('Role mahasiswa tidak ditemukan!');
            return;
        }

        // Buat 100 mahasiswa
        for ($i = 0; $i < 100; $i++) {
            $user = User::factory()->create([
                'role_id' => $roleMahasiswa->id,
            ]);

            Mahasiswa::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('✓ 100 data dummy mahasiswa berhasil dibuat!');
    }
}

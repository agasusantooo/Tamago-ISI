<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role mahasiswa
        $roleMahasiswa = Role::where('name', 'mahasiswa')->first();

        if (! $roleMahasiswa) {
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

        // Pastikan mahasiswa lama / existing punya rumpun_ilmu kalau belum ada
        $rumpuns = ['fotografi', 'film dan televisi', 'animasi', 'produksi film dan televisi'];
        foreach (Mahasiswa::whereNull('rumpun_ilmu')->get() as $m) {
            $m->rumpun_ilmu = $rumpuns[array_rand($rumpuns)];
            $m->save();
        }

        $this->command->info('✓ 100 data dummy mahasiswa berhasil dibuat!');
    }
}

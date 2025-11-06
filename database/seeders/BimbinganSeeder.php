<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\ProjekAkhir;

class BimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahasiswa::create([
            'nim' => '12345678',
            'nama' => 'adminnn',
            'email' => 'admin@gmail.com',
            'status' => 'aktif',
        ]);

        Dosen::create([
            'nidn' => '12345',
            'nama' => 'Dosen 1',
            'jabatan' => 'Dosen',
            'rumpun_ilmu' => 'Informatika',
        ]);

        Dosen::create([
            'nidn' => '54321',
            'nama' => 'Dosen 2',
            'jabatan' => 'Dosen',
            'rumpun_ilmu' => 'Informatika',
        ]);

        ProjekAkhir::create([
            'nim' => '12345678',
            'nidn1' => '12345',
            'nidn2' => '54321',
            'judul' => 'Test Judul',
            'status' => 'berjalan',
        ]);
    }
}

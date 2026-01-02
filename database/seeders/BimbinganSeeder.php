<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\ProjekAkhir;
use Illuminate\Database\Seeder;

class BimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahasiswa::updateOrCreate(
            ['nim' => '12345678'],
            [
                'nama' => 'adminnn',
                'email' => 'admin@gmail.com',
                'status' => 'aktif',
                'rumpun_ilmu' => 'fotografi',
            ]
        );

        Dosen::updateOrCreate(
            ['nidn' => '12345'],
            [
                'nama' => 'Dosen 1',
                'jabatan' => 'Dosen',
                'rumpun_ilmu' => 'Informatika',
            ]
        );

        Dosen::updateOrCreate(
            ['nidn' => '54321'],
            [
                'nama' => 'Dosen 2',
                'jabatan' => 'Dosen',
                'rumpun_ilmu' => 'Informatika',
            ]
        );

        ProjekAkhir::updateOrCreate(
            ['nim' => '12345678'],
            [
                'nidn1' => '12345',
                'nidn2' => '54321',
                'judul' => 'Test Judul',
                'status' => 'berjalan',
            ]
        );
    }
}

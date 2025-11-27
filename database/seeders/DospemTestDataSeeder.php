<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Proposal;
use App\Models\Bimbingan;
use App\Models\Role;

class DospemTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat dosen pembimbing jika belum ada
        $dosenPembimbing = User::where('email', 'dosen1@lecturer.isi.ac.id')->first();
        if (!$dosenPembimbing) {
            $role = Role::where('name', 'dospem')->first() ?? Role::create(['name' => 'dospem', 'display_name' => 'Dosen Pembimbing']);
            
            $dosenPembimbing = User::create([
                'name' => 'Dosen Pembimbing 1',
                'email' => 'dosen1@lecturer.isi.ac.id',
                'password' => bcrypt('password'),
                'role_id' => $role->id
            ]);

            Dosen::create([
                'nidn' => 'NIDN001',
                'nama' => 'Dosen Pembimbing 1',
                'jabatan' => 'Dosen Pembimbing',
                'rumpun_ilmu' => 'Informatika',
                'status' => 'Aktif'
            ]);
        }

        // Buat mahasiswa jika belum ada
        $mahasiswa = Mahasiswa::where('nim', '71220022')->first();
        if (!$mahasiswa) {
            $mahasiswaRole = Role::where('name', 'mahasiswa')->first() ?? Role::create(['name' => 'mahasiswa', 'display_name' => 'Mahasiswa']);
            
            $mahasiswaUser = User::create([
                'name' => 'Debora',
                'email' => 'debora@student.isi.ac.id',
                'password' => bcrypt('password'),
                'role_id' => $mahasiswaRole->id
            ]);

            $mahasiswa = Mahasiswa::create([
                'nim' => '71220022',
                'nama' => 'Debora',
                'email' => 'debora@student.isi.ac.id',
                'status' => 'Aktif',
                'user_id' => $mahasiswaUser->id,
                'dosen_pembimbing_id' => 'NIDN001'
            ]);
        } else {
            // Update mahasiswa jika sudah ada
            $mahasiswa->update(['dosen_pembimbing_id' => 'NIDN001']);
        }

        // Buat proposal jika belum ada
        if (Proposal::where('mahasiswa_nim', '71220022')->count() === 0) {
            Proposal::create([
                'mahasiswa_nim' => '71220022',
                'dosen_id' => 'NIDN001',
                'judul' => 'Sistem Rekomendasi Film Berbasis AI',
                'deskripsi' => 'Penelitian tentang implementasi machine learning untuk rekomendasi film',
                'status' => 'diajukan',
                'tanggal_pengajuan' => now(),
                'versi' => 1
            ]);

            Proposal::create([
                'mahasiswa_nim' => '71220022',
                'dosen_id' => 'NIDN001',
                'judul' => 'Sistem Rekomendasi Film - Revisi v2',
                'deskripsi' => 'Perbaikan berdasarkan feedback dari dosen',
                'status' => 'review',
                'tanggal_pengajuan' => now()->subDays(2),
                'versi' => 2
            ]);
        }

        // Buat jadwal bimbingan jika belum ada
        if (Bimbingan::where('nim', '71220022')->count() === 0) {
            Bimbingan::create([
                'nim' => '71220022',
                'dosen_nidn' => 'NIDN001',
                'tanggal' => now()->addDays(3),
                'waktu_mulai' => '14:00:00',
                'waktu_selesai' => '15:00:00',
                'topik' => 'Diskusi Proposal',
                'status' => 'pending',
                'catatan_bimbingan' => null
            ]);

            Bimbingan::create([
                'nim' => '71220022',
                'dosen_nidn' => 'NIDN001',
                'tanggal' => now()->addDays(7),
                'waktu_mulai' => '15:30:00',
                'waktu_selesai' => '16:30:00',
                'topik' => 'Revisi Bab 3',
                'status' => 'approved',
                'catatan_bimbingan' => null
            ]);
        }

        $this->command->info('Test data for Dospem seeded successfully!');
    }
}

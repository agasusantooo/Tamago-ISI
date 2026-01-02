<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role dospem
        $roleDospem = Role::where('name', 'dospem')->first();

        if (!$roleDospem) {
            $this->command->error('Role dospem tidak ditemukan!');
            return;
        }

        // Data dummy dosen
        $dosenData = [
            [
                'nidn' => '0012345671',
                'nama' => 'Dr. Ahmad Surya, S.Kom., M.Kom.',
                'jabatan' => 'Lektor Kepala',
                'rumpun_ilmu' => 'fotografi',
                'jabatan_fungsional' => 'Lektor Kepala',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345672',
                'nama' => 'Prof. Dr. Budi Santoso, S.T., M.T.',
                'jabatan' => 'Guru Besar',
                'rumpun_ilmu' => 'film dan televisi',
                'jabatan_fungsional' => 'Guru Besar',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345673',
                'nama' => 'Dr. Citra Dewi, S.Kom., M.Kom.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'animasi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345674',
                'nama' => 'Ir. Dedi Pratama, M.T.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'produksi film dan televisi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345675',
                'nama' => 'Dr. Eka Putri, S.T., M.T.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'fotografi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345676',
                'nama' => 'Prof. Dr. Fajar Nugroho, S.Kom., M.Kom.',
                'jabatan' => 'Guru Besar',
                'rumpun_ilmu' => 'film dan televisi',
                'jabatan_fungsional' => 'Guru Besar',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345677',
                'nama' => 'Dr. Gita Sari, S.T., M.T.',
                'jabatan' => 'Lektor Kepala',
                'rumpun_ilmu' => 'animasi',
                'jabatan_fungsional' => 'Lektor Kepala',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345678',
                'nama' => 'Ir. Hadi Wijaya, M.Kom.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'produksi film dan televisi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345679',
                'nama' => 'Dr. Indah Permata, S.Kom., M.Kom.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'fotografi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345680',
                'nama' => 'Prof. Dr. Joko Susilo, S.T., M.T.',
                'jabatan' => 'Guru Besar',
                'rumpun_ilmu' => 'film dan televisi',
                'jabatan_fungsional' => 'Guru Besar',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345681',
                'nama' => 'Dr. Kurniawan Pratama, S.Kom., M.Kom.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'animasi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345682',
                'nama' => 'Dra. Lilis Marlina, M.Kom.',
                'jabatan' => 'Lektor Kepala',
                'rumpun_ilmu' => 'produksi film dan televisi',
                'jabatan_fungsional' => 'Lektor Kepala',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345683',
                'nama' => 'Dr. M. Rinaldi, S.T., M.T.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'fotografi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345684',
                'nama' => 'Ir. Nina Farida, M.T.',
                'jabatan' => 'Lektor Kepala',
                'rumpun_ilmu' => 'film dan televisi',
                'jabatan_fungsional' => 'Lektor Kepala',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345685',
                'nama' => 'Dr. Oka Pratama, S.Kom., M.Kom.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'animasi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345686',
                'nama' => 'Prof. Dr. Putri Handayani, S.Si., M.Sc.',
                'jabatan' => 'Guru Besar',
                'rumpun_ilmu' => 'produksi film dan televisi',
                'jabatan_fungsional' => 'Guru Besar',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345687',
                'nama' => 'Dr. Rina Oktaviani, S.Pd., M.Pd.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'fotografi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345688',
                'nama' => 'Ir. Sigit Haryono, M.Eng.',
                'jabatan' => 'Lektor Kepala',
                'rumpun_ilmu' => 'film dan televisi',
                'jabatan_fungsional' => 'Lektor Kepala',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
            [
                'nidn' => '0012345689',
                'nama' => 'Dr. Tia Kusuma, S.Kom., M.Kom.',
                'jabatan' => 'Lektor',
                'rumpun_ilmu' => 'animasi',
                'jabatan_fungsional' => 'Lektor',
                'status' => 'aktif',
                'is_dosen_seminar' => false,
            ],
            [
                'nidn' => '0012345690',
                'nama' => 'Prof. Dr. Usman Harun, S.T., M.T.',
                'jabatan' => 'Guru Besar',
                'rumpun_ilmu' => 'produksi film dan televisi',
                'jabatan_fungsional' => 'Guru Besar',
                'status' => 'aktif',
                'is_dosen_seminar' => true,
            ],
        ];

        foreach ($dosenData as $data) {
            // Buat (atau ambil) user untuk dosen — idempotent
            $email = strtolower(str_replace([' ', '.', ','], '', $data['nama'])) . '@example.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'role_id' => $roleDospem->id,
                    'name' => $data['nama'],
                    'password' => bcrypt('password'),
                ]
            );

            // Buat atau update record dosen supaya seeder bisa dijalankan ulang tanpa error
            Dosen::updateOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['nama'],
                    'jabatan' => $data['jabatan'],
                    'rumpun_ilmu' => $data['rumpun_ilmu'],
                    'jabatan_fungsional' => $data['jabatan_fungsional'],
                    'status' => $data['status'],
                    'is_dosen_seminar' => $data['is_dosen_seminar'],
                ]
            );
        }

        $this->command->info('✓ 20 data dummy dosen berhasil dibuat atau diperbarui!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role dari database
        $roleMahasiswa = Role::where('name', 'mahasiswa')->first();
        $roleDospem = Role::where('name', 'dospem')->first();
        $roleKaprodi = Role::where('name', 'kaprodi')->first();
        $roleKoordinatorTA = Role::where('name', 'koordinator_ta')->first();
        $roleDosenPenguji = Role::where('name', 'dosen_penguji')->first();
        $roleAdmin = Role::where('name', 'admin')->first();
        $roleKoordinatorTefa = Role::where('name', 'koordinator_tefa')->first();

        // Buat user demo untuk setiap role
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'mahasiswa@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleMahasiswa->id,
            ],
            [
                'name' => 'Admin System',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleAdmin->id,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // === Buat Dosen Demo dalam jumlah banyak ===
        $jabatanRanks = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];
        $dosenNames = [
            'Dr. Sarah Wijaya', 'Prof. Ahmad Rahman', 'Dr. Siti Aminah', 'Dr. Indah Permata', 'Dr. Rendi Pratama',
            'Prof. Dr. Dewi Lestari', 'Dr. Bambang Susanto', 'Dr. Fitriani Hartono', 'Agus Setiawan, M.Kom.', 'Dr. Dian Novitasari',
            'Muhammad Iqbal, M.Sn.', 'Dr. Eko Prasetyo', 'Lina Marlina, M.Ds.', 'Dr. Yuniar Supriadi', 'Dr. Heru Wibowo'
        ];

        $dosenEmails = [
            'dospem@test.com', 'kaprodi@test.com', 'koordinator_ta@test.com', 'dosen_penguji@test.com', 'koordinator_tefa@test.com'
        ];

        $dosenRoles = [
            'dospem@test.com' => $roleDospem->id,
            'kaprodi@test.com' => $roleKaprodi->id,
            'koordinator_ta@test.com' => $roleKoordinatorTA->id,
            'dosen_penguji@test.com' => $roleDosenPenguji->id,
            'koordinator_tefa@test.com' => $roleKoordinatorTefa->id,
        ];
        
        $i = 0;
        foreach ($dosenNames as $name) {
            $email = Str::slug($name) . '@test.com';
            
            // Override email for specific roles
            if (isset($dosenEmails[$i])) {
                $email = $dosenEmails[$i];
            }

            // Assign a role, default to dospem
            $role_id = $dosenRoles[$email] ?? $roleDospem->id;
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role_id' => $role_id,
                ]
            );

            Dosen::firstOrCreate(
                ['nidn' => '00112233' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $user->id,
                    'nama' => $name,
                    'jabatan' => 'Dosen',
                    'rumpun_ilmu' => 'Umum', // Default value
                    'jabatan_fungsional' => $jabatanRanks[array_rand($jabatanRanks)],
                    'status' => 'aktif'
                ]
            );
            $i++;
        }

        $this->command->info('✓ Demo users & dosens berhasil dibuat!');
        $this->command->info('');
        $this->command->info('Login credentials: password');
        $this->command->info('Mahasiswa   : mahasiswa@test.com');
        $this->command->info('Dosen       : dospem@test.com');
        $this->command->info('Kaprodi     : kaprodi@test.com');
        $this->command->info('Koordinator : koordinator_ta@test.com');
        $this->command->info('Penguji     : dosen_penguji@test.com');
        $this->command->info('Admin       : admin@test.com');
    }
}
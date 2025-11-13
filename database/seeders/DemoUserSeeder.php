<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

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

        // Buat user demo untuk setiap role
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'mahasiswa@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleMahasiswa->id,
            ],
            [
                'name' => 'Dr. Sarah Wijaya',
                'email' => 'dospem@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleDospem->id,
            ],
            [
                'name' => 'Prof. Ahmad Rahman',
                'email' => 'kaprodi@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleKaprodi->id,
            ],
            [
                'name' => 'Dr. Siti Aminah',
                'email' => 'koordinator_ta@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleKoordinatorTA->id,
            ],
            [
                'name' => 'Dr. Indah Permata',
                'email' => 'dosen_penguji@test.com',
                'password' => Hash::make('password'),
                'role_id' => $roleDosenPenguji->id,
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

        $this->command->info('✓ Demo users berhasil dibuat!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Mahasiswa   : mahasiswa@test.com / password');
        $this->command->info('Dosen       : dospem@test.com / password');
        $this->command->info('Kaprodi     : kaprodi@test.com / password');
        $this->command->info('Koordinator : koordinator_ta@test.com / password');
        $this->command->info('Penguji     : dosen_penguji@test.com / password');
        $this->command->info('Admin       : admin@test.com / password');
    }
}
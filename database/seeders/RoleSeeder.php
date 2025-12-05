<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder

{

    /**

     * Run the database seeds.

     */

    public function run(): void

    {

        $roles = [

            [

                'name' => 'mahasiswa',

                'display_name' => 'Mahasiswa',

                'description' => 'Role untuk mahasiswa yang mengerjakan Tugas Akhir'

            ],

            [

                'name' => 'dospem',

                'display_name' => 'Dosen Pembimbing',

                'description' => 'Role untuk dosen pembimbing mahasiswa TA'

            ],

            [

                'name' => 'kaprodi',

                'display_name' => 'Koordinator Prodi',

                'description' => 'Role untuk koordinator program studi'

            ],

            [

                'name' => 'koordinator_ta',

                'display_name' => 'Koordinator TA',

                'description' => 'Role untuk koordinator tugas akhir'

            ],

            [

                'name' => 'dosen_penguji',

                'display_name' => 'Dosen Penguji',

                'description' => 'Role untuk dosen penguji sidang TA'

            ],

            [

                'name' => 'admin',

                'display_name' => 'Administrator',

                'description' => 'Role untuk administrator sistem'

            ],

            [

                'name' => 'koordinator_tefa',

                'display_name' => 'Koordinator TEFA',

                'description' => 'Role untuk koordinator TEFA Fair'

            ]

        ];



        foreach ($roles as $role) {

            Role::firstOrCreate(['name' => $role['name']], $role);

        }



        $this->command->info('✓ Roles berhasil disinkronkan!');

    }

}
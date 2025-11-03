<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Tambah kolom role_id di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')
                  ->constrained('roles')->onDelete('set null');
        });

        // 3. Insert data roles
        DB::table('roles')->insert([
            [
                'name' => 'mahasiswa',
                'display_name' => 'Mahasiswa',
                'description' => 'Mahasiswa yang sedang mengerjakan TA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'dospem',
                'display_name' => 'Dosen Pembimbing',
                'description' => 'Dosen pembimbing tugas akhir',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'kaprodi',
                'display_name' => 'Koordinator Prodi',
                'description' => 'Koordinator program studi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'koordinator_ta',
                'display_name' => 'Koordinator TA',
                'description' => 'Koordinator tugas akhir fakultas',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrator sistem',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'dosen_penguji',
                'display_name' => 'Dosen Penguji',
                'description' => 'Dosen penguji ujian TA',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        Schema::dropIfExists('roles');
    }
};
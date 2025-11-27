<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswa', 'angkatan')) {
                $table->dropColumn('angkatan');
            }
            if (Schema::hasColumn('mahasiswa', 'prodi')) {
                $table->dropColumn('prodi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (! Schema::hasColumn('mahasiswa', 'prodi')) {
                $table->string('prodi')->nullable()->after('nama');
            }
            if (! Schema::hasColumn('mahasiswa', 'angkatan')) {
                $table->string('angkatan')->nullable()->after('prodi');
            }
        });
    }
};

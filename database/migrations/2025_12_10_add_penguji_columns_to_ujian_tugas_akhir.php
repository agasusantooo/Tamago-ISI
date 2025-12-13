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
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            // Add penguji columns (references to users table)
            $table->unsignedBigInteger('ketua_penguji_id')->nullable()->after('dosen_pembimbing_id');
            $table->unsignedBigInteger('penguji_ahli_id')->nullable()->after('ketua_penguji_id');
            
            // Add foreign key constraints
            $table->foreign('ketua_penguji_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('penguji_ahli_id')->references('id')->on('users')->onDelete('set null');
            
            // Add nilai_akhir column for storing grades
            $table->decimal('nilai_akhir', 5, 2)->nullable()->after('penguji_ahli_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            $table->dropForeign(['ketua_penguji_id']);
            $table->dropForeign(['penguji_ahli_id']);
            $table->dropColumn(['ketua_penguji_id', 'penguji_ahli_id', 'nilai_akhir']);
        });
    }
};

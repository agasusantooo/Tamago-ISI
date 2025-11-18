<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to avoid requiring the doctrine/dbal dependency for column changes
        DB::statement('ALTER TABLE `ujian_tugas_akhir` MODIFY `tanggal_ujian` DATE NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `ujian_tugas_akhir` MODIFY `tanggal_ujian` DATE NOT NULL');
    }
};

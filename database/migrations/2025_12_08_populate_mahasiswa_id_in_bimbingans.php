<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Populate mahasiswa_id based on nim
     */
    public function up(): void
    {
        // Update bimbingans.mahasiswa_id from mahasiswa table using nim
        DB::update(
            "UPDATE bimbingans b
            INNER JOIN mahasiswa m ON b.nim = m.nim
            SET b.mahasiswa_id = m.user_id
            WHERE b.mahasiswa_id IS NULL OR b.mahasiswa_id = 0"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot really reverse this safely
    }
};

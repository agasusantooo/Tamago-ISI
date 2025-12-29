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
        // Update bimbingans.mahasiswa_id from mahasiswa table using nim.
        // Use DB-driver specific SQL so tests (SQLite) or other DBs work.
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::update(
                'UPDATE bimbingans b
                INNER JOIN mahasiswa m ON b.nim = m.nim
                SET b.mahasiswa_id = m.user_id
                WHERE b.mahasiswa_id IS NULL OR b.mahasiswa_id = 0'
            );
        } elseif ($driver === 'pgsql') {
            // PostgreSQL supports UPDATE FROM
            DB::update(
                'UPDATE bimbingans b
                SET mahasiswa_id = m.user_id
                FROM mahasiswa m
                WHERE b.nim = m.nim
                AND (b.mahasiswa_id IS NULL OR b.mahasiswa_id = 0)'
            );
        } else {
            // SQLite (and other drivers): use a correlated subquery
            DB::statement(
                'UPDATE bimbingans
                SET mahasiswa_id = (
                    SELECT user_id FROM mahasiswa WHERE mahasiswa.nim = bimbingans.nim
                )
                WHERE mahasiswa_id IS NULL OR mahasiswa_id = 0'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot really reverse this safely
    }
};

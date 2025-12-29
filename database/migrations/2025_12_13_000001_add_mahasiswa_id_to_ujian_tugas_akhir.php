<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds mahasiswa_id (unsigned bigint) as nullable to allow backfill.
     */
    public function up()
    {
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            if (! Schema::hasColumn('ujian_tugas_akhir', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('id_proyek_akhir');
            }
        });

        // Backfill mahasiswa_id from projek_akhir->nim -> mahasiswa.user_id
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support JOIN in UPDATE the same way; use a correlated subquery
            DB::statement(
                'UPDATE ujian_tugas_akhir
                 SET mahasiswa_id = (
                     SELECT m.user_id FROM projek_akhir p JOIN mahasiswa m ON p.nim = m.nim
                     WHERE p.id_proyek_akhir = ujian_tugas_akhir.id_proyek_akhir
                 )
                 WHERE mahasiswa_id IS NULL
                 AND EXISTS (
                     SELECT 1 FROM projek_akhir p JOIN mahasiswa m ON p.nim = m.nim
                     WHERE p.id_proyek_akhir = ujian_tugas_akhir.id_proyek_akhir
                 )'
            );
        } else {
            DB::statement(
                'UPDATE ujian_tugas_akhir u
                JOIN projek_akhir p ON u.id_proyek_akhir = p.id_proyek_akhir
                JOIN mahasiswa m ON p.nim = m.nim
                SET u.mahasiswa_id = m.user_id
                WHERE u.mahasiswa_id IS NULL'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            if (Schema::hasColumn('ujian_tugas_akhir', 'mahasiswa_id')) {
                $table->dropColumn('mahasiswa_id');
            }
        });
    }
};

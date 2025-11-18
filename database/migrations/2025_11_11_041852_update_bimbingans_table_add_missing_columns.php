<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            // Tambah kolom yang diperlukan jika belum ada
            if (!Schema::hasColumn('bimbingans', 'topik')) {
                $table->string('topik')->nullable()->after('id_proyek_akhir');
            }
            if (!Schema::hasColumn('bimbingans', 'catatan_mahasiswa')) {
                $table->text('catatan_mahasiswa')->nullable()->after('topik');
            }
            if (!Schema::hasColumn('bimbingans', 'catatan_dosen')) {
                $table->text('catatan_dosen')->nullable()->after('catatan_mahasiswa');
            }
            if (!Schema::hasColumn('bimbingans', 'file_pendukung')) {
                $table->string('file_pendukung')->nullable()->after('catatan_dosen');
            }

            // Rename kolom status_persetujuan menjadi status jika perlu
            if (Schema::hasColumn('bimbingans', 'status_persetujuan') && !Schema::hasColumn('bimbingans', 'status')) {
                $table->renameColumn('status_persetujuan', 'status');
            }

            // Tambah kolom waktu & ruang untuk jadwal jika belum ada
            if (!Schema::hasColumn('bimbingans', 'waktu_mulai')) {
                $table->time('waktu_mulai')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('bimbingans', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
            }
            if (!Schema::hasColumn('bimbingans', 'ruang')) {
                $table->string('ruang')->nullable()->after('waktu_selesai');
            }

            // Make nidn nullable if the column exists
            if (Schema::hasColumn('bimbingans', 'nidn')) {
                try {
                    $table->string('nidn')->nullable()->change();
                } catch (\Exception $e) {
                    // Some DB drivers / older versions may not support change(); ignore safe-fail
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            // Drop columns if they exist
            $cols = [];
            foreach (['topik', 'catatan_mahasiswa', 'catatan_dosen', 'file_pendukung', 'waktu_mulai', 'waktu_selesai', 'ruang'] as $c) {
                if (Schema::hasColumn('bimbingans', $c)) {
                    $cols[] = $c;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }

            if (Schema::hasColumn('bimbingans', 'status') && !Schema::hasColumn('bimbingans', 'status_persetujuan')) {
                $table->renameColumn('status', 'status_persetujuan');
            }
        });
    }
};
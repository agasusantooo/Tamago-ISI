<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            // Tambah kolom yang belum ada
            if (!Schema::hasColumn('bimbingans', 'catatan_dosen')) {
                $table->text('catatan_dosen')->nullable()->after('catatan_mahasiswa');
            }
            if (!Schema::hasColumn('bimbingans', 'waktu_mulai')) {
                $table->time('waktu_mulai')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('bimbingans', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
            }
            if (!Schema::hasColumn('bimbingans', 'ruang')) {
                $table->string('ruang')->nullable()->after('waktu_selesai');
            }

            // Hapus kolom duplikat status_persetujuan (karena sudah ada 'status')
            if (Schema::hasColumn('bimbingans', 'status_persetujuan')) {
                try {
                    $table->dropColumn('status_persetujuan');
                } catch (\Exception $e) {
                    // ignore if cannot drop
                }
            }

            // Make nidn nullable (karena mahasiswa yang submit belum tentu ada dosennya)
            if (Schema::hasColumn('bimbingans', 'nidn')) {
                try {
                    $table->string('nidn')->nullable()->change();
                } catch (\Exception $e) {
                    // ignore if change not supported
                }
            }

            // Make nim nullable also if exists
            if (Schema::hasColumn('bimbingans', 'nim')) {
                try {
                    $table->string('nim')->nullable()->change();
                } catch (\Exception $e) {
                    // ignore
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $cols = [];
            foreach (['catatan_dosen', 'waktu_mulai', 'waktu_selesai', 'ruang'] as $c) {
                if (Schema::hasColumn('bimbingans', $c)) {
                    $cols[] = $c;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }

            if (!Schema::hasColumn('bimbingans', 'status_persetujuan') && Schema::hasColumn('bimbingans', 'status')) {
                $table->string('status_persetujuan')->default('pending')->after('status');
            }
        });
    }
};
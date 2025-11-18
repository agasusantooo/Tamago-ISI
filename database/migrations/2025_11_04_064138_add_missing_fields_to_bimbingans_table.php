<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            if (!Schema::hasColumn('bimbingans', 'catatan_mahasiswa')) {
                $table->text('catatan_mahasiswa')->nullable()->after('topik');
            }
            if (!Schema::hasColumn('bimbingans', 'file_pendukung')) {
                $table->string('file_pendukung')->nullable()->after('catatan_mahasiswa');
            }
            if (!Schema::hasColumn('bimbingans', 'status')) {
                $table->string('status')->default('pending')->after('file_pendukung');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            // $table->dropColumn(['catatan_mahasiswa', 'file_pendukung', 'status']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->string('topik')->nullable()->after('id_bimbingan');
            $table->text('catatan_mahasiswa')->nullable()->after('topik');
            $table->string('file_pendukung')->nullable()->after('catatan_mahasiswa');
            $table->string('status')->default('pending')->after('file_pendukung');
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn(['topik', 'catatan_mahasiswa', 'file_pendukung', 'status']);
        });
    }
};

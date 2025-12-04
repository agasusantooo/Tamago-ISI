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
        Schema::table('tefa_fair', function (Blueprint $table) {
            $table->enum('status', ['menunggu_review', 'disetujui', 'ditolak'])->default('menunggu_review')->after('daftar_kebutuhan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tefa_fair', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
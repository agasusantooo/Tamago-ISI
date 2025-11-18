<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_conference', function (Blueprint $table) {
            // Make all NOT NULL columns nullable to allow flexible INSERT
            $table->string('mahasiswa_nim')->nullable()->change();
            $table->unsignedBigInteger('proposals_id')->nullable()->change();
            $table->date('tanggal')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('story_conference', function (Blueprint $table) {
            // Revert if needed (though this may fail if data contains NULLs)
            // Leave as is for safety
        });
    }
};

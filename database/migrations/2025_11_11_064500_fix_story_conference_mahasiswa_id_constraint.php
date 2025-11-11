<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_conference', function (Blueprint $table) {
            // Make mahasiswa_id nullable temporarily to fix existing NULL values
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('story_conference', function (Blueprint $table) {
            // Revert mahasiswa_id back to NOT NULL (if needed)
            $table->unsignedBigInteger('mahasiswa_id')->nullable(false)->change();
        });
    }
};

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
        Schema::create('dosen_rumpun_ilmu', function (Blueprint $table) {
            $table->string('dosen_nidn');
            $table->foreignId('rumpun_ilmu_id')->constrained('rumpun_ilmus')->onDelete('cascade');
            
            $table->primary(['dosen_nidn', 'rumpun_ilmu_id']);
            $table->foreign('dosen_nidn')->references('nidn')->on('dosen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_rumpun_ilmu');
    }
};

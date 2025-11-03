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
        Schema::create('dosen_penguji', function (Blueprint $table) {
            $table->id('id_penguji');
            $table->unsignedBigInteger('id_ujian');
            $table->string('nidn');
            $table->integer('nilai')->nullable();
            $table->text('catatan')->nullable();
            $table->string('peran_penguji');
            $table->timestamps();

            $table->foreign('id_ujian')->references('id_ujian')->on('ujian_tugas_akhir')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('dosen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_penguji');
    }
};

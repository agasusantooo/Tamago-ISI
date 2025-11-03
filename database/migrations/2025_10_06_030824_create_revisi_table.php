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
        Schema::create('revisi', function (Blueprint $table) {
            $table->id('id_revisi');
            $table->unsignedBigInteger('id_ujian');
            $table->text('deskripsi');
            $table->string('status')->default('belum');
            $table->timestamps();

            $table->foreign('id_ujian')->references('id_ujian')->on('ujian_tugas_akhir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi');
    }
};

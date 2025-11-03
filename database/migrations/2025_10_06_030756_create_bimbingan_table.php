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
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id('id_bimbingan');
            $table->unsignedBigInteger('id_proyek_akhir');
            $table->string('nidn');
            $table->date('tanggal');
            $table->text('catatan_bimbingan')->nullable();
            $table->string('pencapaian')->nullable();
            $table->string('status_persetujuan')->default('pending');
            $table->timestamps();

            $table->foreign('id_proyek_akhir')->references('id_proyek_akhir')->on('projek_akhir')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('dosen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan');
    }
};

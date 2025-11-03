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
        Schema::create('ujian_tugas_akhir', function (Blueprint $table) {
            $table->id('id_ujian');
            $table->unsignedBigInteger('id_proyek_akhir');
            $table->date('tanggal_ujian');
            $table->string('hasil_akhir')->nullable();
            $table->text('catatan_penguj')->nullable();
            $table->string('status_kelayakan')->default('belum');
            $table->timestamps();

            $table->foreign('id_proyek_akhir')->references('id_proyek_akhir')->on('projek_akhir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_tugas_akhir');
    }
};

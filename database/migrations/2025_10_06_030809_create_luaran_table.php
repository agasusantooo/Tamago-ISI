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
        Schema::create('luaran', function (Blueprint $table) {
            $table->id('id_luaran');
            $table->unsignedBigInteger('id_proyek_akhir');
            $table->string('jenis_luaran');
            $table->string('file_url');
            $table->date('tanggal_unggah');
            $table->timestamps();

            $table->foreign('id_proyek_akhir')->references('id_proyek_akhir')->on('projek_akhir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luaran');
    }
};

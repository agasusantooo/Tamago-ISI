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
        Schema::create('tefa_fair', function (Blueprint $table) {
            $table->id('id_tefa');
            $table->unsignedBigInteger('id_proyek_akhir');
            $table->string('semester');
            $table->string('file_presentasi')->nullable();
            $table->string('daftar_kebutuhan')->nullable();
            $table->timestamps();

            $table->foreign('id_proyek_akhir')->references('id_proyek_akhir')->on('projek_akhir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tefa_fair');
    }
};

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
        Schema::create('projek_akhir', function (Blueprint $table) {
           $table->id('id_proyek_akhir');
            $table->string('nim');
            $table->string('nidn1')->nullable();
            $table->string('nidn2')->nullable();
            $table->string('judul');
            $table->string('file_proposal')->nullable();
            $table->string('file_pitch_deck')->nullable();
            $table->string('file_story_bible')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('nidn1')->references('nidn')->on('dosen')->onDelete('set null');
            $table->foreign('nidn2')->references('nidn')->on('dosen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projek_akhir');
    }
};

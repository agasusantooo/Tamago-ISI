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
        Schema::create('story_conference', function (Blueprint $table) {
            $table->id('id_conference');
            $table->string('mahasiswa_nim'); // kolom untuk foreign key ke mahasiswa.nim
            $table->unsignedBigInteger('proposals_id');
            $table->date('tanggal');
            $table->text('catatan_evaluasi')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            // relasi ke tabel mahasiswa (nim) dan proposal (id)
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('proposals_id')->references('id')->on('proposals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_conference');
    }
};

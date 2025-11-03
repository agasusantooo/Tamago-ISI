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
            $table->unsignedBigInteger('id_proyek_akhir');
            $table->date('tanggal');
            $table->text('catatan_evaluasi')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('id_proyek_akhir')->references('id_proyek_akhir')->on('projek_akhir')->onDelete('cascade');
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

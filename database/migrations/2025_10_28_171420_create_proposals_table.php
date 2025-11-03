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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (mahasiswa)
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');

            // Relasi ke dosen (nidn = string)
            $table->string('dosen_id')->nullable();
            $table->foreign('dosen_id')->references('nidn')->on('dosen')->onDelete('set null');

            $table->string('judul');
            $table->text('deskripsi');
            $table->string('file_proposal')->nullable();
            $table->string('file_pitch_deck')->nullable();
            $table->integer('versi')->default(1);
            $table->enum('status', ['draft', 'diajukan', 'review', 'revisi', 'disetujui', 'ditolak'])->default('draft');
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('mahasiswa_id');
            $table->index('dosen_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};

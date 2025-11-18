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
        Schema::create('tim_produksi', function (Blueprint $table) {
            $table->id();

            // Relasi ke mahasiswa (users)
            $table->foreignId('mahasiswa_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Relasi ke proposal
            $table->foreignId('proposal_id')
                  ->constrained('proposals')
                  ->onDelete('cascade');

            // Relasi ke dosen (nidn, string)
            $table->string('dosen_id')->nullable();
            $table->foreign('dosen_id')
                  ->references('nidn')
                  ->on('dosen')
                  ->onDelete('set null');

            // Pra Produksi Files
            $table->string('file_skenario')->nullable();
            $table->string('file_storyboard')->nullable();
            $table->string('file_dokumen_pendukung')->nullable();

            // Produksi Akhir
            $table->string('file_produksi_akhir')->nullable();
            $table->string('file_luaran_tambahan')->nullable(); // Artbook/Poster/Teaser/Infografis
            $table->text('catatan_produksi')->nullable();

            // Status: belum_upload, menunggu_review, disetujui, revisi, ditolak
            $table->enum('status_pra_produksi', [
                'belum_upload',
                'menunggu_review',
                'disetujui',
                'revisi',
                'ditolak'
            ])->default('belum_upload');

            $table->enum('status_produksi_akhir', [
                'belum_upload',
                'menunggu_review',
                'disetujui',
                'revisi',
                'ditolak'
            ])->default('belum_upload');

            // Timestamps dan feedback
            $table->timestamp('tanggal_upload_pra')->nullable();
            $table->timestamp('tanggal_upload_akhir')->nullable();
            $table->timestamp('tanggal_review_pra')->nullable();
            $table->timestamp('tanggal_review_akhir')->nullable();
            $table->text('feedback_pra_produksi')->nullable();
            $table->text('feedback_produksi_akhir')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('mahasiswa_id');
            $table->index('proposal_id');
            $table->index('dosen_id');
            $table->index('status_pra_produksi');
            $table->index('status_produksi_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tim_produksi');
    }
};

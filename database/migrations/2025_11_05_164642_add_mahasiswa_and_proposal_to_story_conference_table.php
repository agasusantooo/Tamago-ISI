<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_conference', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->after('id_conference');
            $table->unsignedBigInteger('proposal_id')->after('mahasiswa_id');
            $table->unsignedBigInteger('dosen_id')->nullable()->after('proposal_id');
            $table->string('judul_karya')->nullable();
            $table->string('slot_waktu')->nullable();
            $table->string('file_presentasi')->nullable();
            $table->timestamp('tanggal_daftar')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            $table->string('catatan_panitia')->nullable();
            $table->string('ruang')->nullable();
            $table->timestamp('waktu_presentasi')->nullable();

            // optional: foreign keys
            // $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
            // $table->foreign('proposal_id')->references('id')->on('proposal')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('story_conference', function (Blueprint $table) {
            $table->dropColumn([
                'mahasiswa_id', 'proposal_id', 'dosen_id', 'judul_karya',
                'slot_waktu', 'file_presentasi', 'tanggal_daftar', 'tanggal_review',
                'catatan_panitia', 'ruang', 'waktu_presentasi'
            ]);
        });
    }
};

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
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            // file uploads
            $table->string('file_surat_pengantar')->nullable()->after('id_proyek_akhir');
            $table->string('file_transkrip_nilai')->nullable()->after('file_surat_pengantar');
            $table->string('file_revisi')->nullable()->after('file_transkrip_nilai');

            // statuses
            $table->string('status_pendaftaran')->default('pengajuan_ujian')->after('file_revisi');
            $table->string('status_ujian')->default('belum_ujian')->after('status_pendaftaran');
            $table->string('status_revisi')->default('belum_revisi')->after('status_ujian');

            // timestamps related to registration & revisi
            $table->timestamp('tanggal_daftar')->nullable()->after('status_revisi');
            $table->timestamp('tanggal_submit_revisi')->nullable()->after('tanggal_daftar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujian_tugas_akhir', function (Blueprint $table) {
            $table->dropColumn([
                'file_surat_pengantar',
                'file_transkrip_nilai',
                'file_revisi',
                'status_pendaftaran',
                'status_ujian',
                'status_revisi',
                'tanggal_daftar',
                'tanggal_submit_revisi',
            ]);
        });
    }
};

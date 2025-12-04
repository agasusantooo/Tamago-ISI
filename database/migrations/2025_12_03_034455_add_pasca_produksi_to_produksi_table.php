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
        Schema::table('tim_produksi', function (Blueprint $table) {
            // Rename 'produksi_akhir' columns to just 'produksi'
            $table->renameColumn('status_produksi_akhir', 'status_produksi');
            $table->renameColumn('file_produksi_akhir', 'file_produksi');
            $table->renameColumn('feedback_produksi_akhir', 'feedback_produksi');
            $table->renameColumn('tanggal_upload_akhir', 'tanggal_upload_produksi');
            $table->renameColumn('tanggal_review_akhir', 'tanggal_review_produksi');

            // Add new 'pasca_produksi' columns
            $table->enum('status_pasca_produksi', ['belum_upload', 'menunggu_review', 'disetujui', 'revisi', 'ditolak'])->default('belum_upload')->after('tanggal_review_produksi');
            $table->string('file_pasca_produksi')->nullable()->after('status_pasca_produksi');
            $table->text('feedback_pasca_produksi')->nullable()->after('file_pasca_produksi');
            $table->timestamp('tanggal_upload_pasca')->nullable()->after('feedback_pasca_produksi');
            $table->timestamp('tanggal_review_pasca')->nullable()->after('tanggal_upload_pasca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tim_produksi', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'status_pasca_produksi',
                'file_pasca_produksi',
                'feedback_pasca_produksi',
                'tanggal_upload_pasca',
                'tanggal_review_pasca',
            ]);

            // Rename 'produksi' columns back to 'produksi_akhir'
            $table->renameColumn('status_produksi', 'status_produksi_akhir');
            $table->renameColumn('file_produksi', 'file_produksi_akhir');
            $table->renameColumn('feedback_produksi', 'feedback_produksi_akhir');
            $table->renameColumn('tanggal_upload_produksi', 'tanggal_upload_akhir');
            $table->renameColumn('tanggal_review_produksi', 'tanggal_review_akhir');
        });
    }
};
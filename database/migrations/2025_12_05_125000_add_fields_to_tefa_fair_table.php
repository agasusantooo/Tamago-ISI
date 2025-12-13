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
        Schema::table('tefa_fair', function (Blueprint $table) {
            // Add mahasiswa_nim column if it doesn't exist
            if (!Schema::hasColumn('tefa_fair', 'mahasiswa_nim')) {
                $table->string('mahasiswa_nim')->nullable()->after('proposal_id');
            }
            
            // Add slot_id to track which jadwal slot was selected
            if (!Schema::hasColumn('tefa_fair', 'slot_id')) {
                $table->unsignedBigInteger('slot_id')->nullable()->after('mahasiswa_nim');
                $table->foreign('slot_id')->references('id')->on('jadwal_tefa_fair')->onDelete('set null');
            }
            
            // Add file_slide for uploaded presentation/slide
            if (!Schema::hasColumn('tefa_fair', 'file_slide')) {
                $table->string('file_slide')->nullable()->after('file_presentasi');
            }
            
            // Add resource requirements field
            if (!Schema::hasColumn('tefa_fair', 'daftar_sumber_daya')) {
                $table->text('daftar_sumber_daya')->nullable()->after('daftar_kebutuhan');
            }
            
            // Add mata_kuliah field to track which course
            if (!Schema::hasColumn('tefa_fair', 'mata_kuliah')) {
                $table->string('mata_kuliah')->nullable()->after('semester');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tefa_fair', function (Blueprint $table) {
            if (Schema::hasColumn('tefa_fair', 'daftar_sumber_daya')) {
                $table->dropColumn('daftar_sumber_daya');
            }
            if (Schema::hasColumn('tefa_fair', 'file_slide')) {
                $table->dropColumn('file_slide');
            }
            if (Schema::hasColumn('tefa_fair', 'slot_id')) {
                $table->dropForeign(['slot_id']);
                $table->dropColumn('slot_id');
            }
            if (Schema::hasColumn('tefa_fair', 'mahasiswa_nim')) {
                $table->dropColumn('mahasiswa_nim');
            }
            if (Schema::hasColumn('tefa_fair', 'mata_kuliah')) {
                $table->dropColumn('mata_kuliah');
            }
        });
    }
};

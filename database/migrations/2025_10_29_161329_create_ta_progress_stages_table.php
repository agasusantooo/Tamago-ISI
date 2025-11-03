<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ta_progress_stages', function (Blueprint $table) {
            $table->id();
            $table->string('stage_code', 50)->unique(); // proposal, bimbingan, story_conference, etc
            $table->string('stage_name', 100);
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2); // Bobot dalam persen (contoh: 20.00)
            $table->integer('sequence'); // Urutan tahapan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default stages
        DB::table('ta_progress_stages')->insert([
            [
                'stage_code' => 'proposal_submission',
                'stage_name' => 'Pengajuan Proposal',
                'description' => 'Mahasiswa mengajukan proposal TA',
                'weight' => 15.00,
                'sequence' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'proposal_approved',
                'stage_name' => 'Proposal Disetujui',
                'description' => 'Proposal telah disetujui oleh pembimbing/kaprodi',
                'weight' => 10.00,
                'sequence' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'bimbingan_progress',
                'stage_name' => 'Bimbingan (Min. 8x)',
                'description' => 'Melakukan bimbingan minimal 8 kali',
                'weight' => 25.00,
                'sequence' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'story_conference',
                'stage_name' => 'Story Conference',
                'description' => 'Mengikuti story conference',
                'weight' => 10.00,
                'sequence' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'production_upload',
                'stage_name' => 'Upload Produksi',
                'description' => 'Mengunggah hasil produksi/karya',
                'weight' => 15.00,
                'sequence' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'exam_registration',
                'stage_name' => 'Pendaftaran Ujian TA',
                'description' => 'Mendaftar ujian tugas akhir',
                'weight' => 10.00,
                'sequence' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'exam_completed',
                'stage_name' => 'Ujian TA Selesai',
                'description' => 'Telah melaksanakan ujian TA',
                'weight' => 10.00,
                'sequence' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stage_code' => 'final_submission',
                'stage_name' => 'Submit Naskah & Karya Final',
                'description' => 'Mengunggah naskah dan karya final',
                'weight' => 5.00,
                'sequence' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_progress_stages');
    }
};
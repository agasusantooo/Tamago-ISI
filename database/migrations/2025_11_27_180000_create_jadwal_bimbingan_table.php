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
        // If table already exists, just add missing columns
        if (Schema::hasTable('jadwal')) {
            Schema::table('jadwal', function (Blueprint $table) {
                // Add columns if they don't exist
                if (!Schema::hasColumn('jadwal', 'nim')) {
                    $table->string('nim')->nullable();
                }
                if (!Schema::hasColumn('jadwal', 'nidn')) {
                    $table->string('nidn')->nullable();
                }
                if (!Schema::hasColumn('jadwal', 'status')) {
                    $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                }
                if (!Schema::hasColumn('jadwal', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
                if (!Schema::hasColumn('jadwal', 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable();
                }
                if (!Schema::hasColumn('jadwal', 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable();
                }
                if (!Schema::hasColumn('jadwal', 'rejected_by')) {
                    $table->unsignedBigInteger('rejected_by')->nullable();
                }
                if (!Schema::hasColumn('jadwal', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable();
                }
            });
        } else {
            // Create table from scratch
            Schema::create('jadwal', function (Blueprint $table) {
                $table->id();
                $table->string('nim'); // Mahasiswa NIM (foreign key to mahasiswa table)
                $table->string('nidn'); // Dosen NIDN (foreign key to dosen table)
                $table->date('tanggal');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->string('tempat')->nullable();
                $table->text('topik')->nullable();
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();

                // Foreign Keys
                $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
                $table->foreign('nidn')->references('nidn')->on('dosen')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};

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
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('ta_progress_stage_id')->constrained('ta_progress_stages')->onDelete('cascade');
            $table->date('due_date');
            $table->timestamps();

            // Add a unique constraint to prevent duplicate entries for the same stage in the same semester
            $table->unique(['semester_id', 'ta_progress_stage_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};

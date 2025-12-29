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
        // Shift sequences for stages at or after position 5 (production_upload and later)
        DB::table('ta_progress_stages')->where('sequence', '>=', 5)->increment('sequence');

        // Insert TEFA registration stage at sequence 5
        DB::table('ta_progress_stages')->insert([
            'stage_code' => 'tefa_registration',
            'stage_name' => 'Pendaftaran TEFA',
            'description' => 'Pendaftaran TEFA Fair',
            'weight' => 10.00,
            'sequence' => 5,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the TEFA stage
        DB::table('ta_progress_stages')->where('stage_code', 'tefa_registration')->delete();

        // Shift sequences back down for stages that were bumped
        DB::table('ta_progress_stages')->where('sequence', '>', 5)->decrement('sequence');
    }
};

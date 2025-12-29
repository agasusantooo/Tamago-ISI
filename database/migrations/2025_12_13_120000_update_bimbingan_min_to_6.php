<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('ta_progress_stages')
            ->where('stage_code', 'bimbingan_progress')
            ->update([
                'stage_name' => 'Bimbingan (Min. 6x)',
                'description' => 'Melakukan bimbingan minimal 6 kali',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ta_progress_stages')
            ->where('stage_code', 'bimbingan_progress')
            ->update([
                'stage_name' => 'Bimbingan (Min. 8x)',
                'description' => 'Melakukan bimbingan minimal 8 kali',
                'updated_at' => now(),
            ]);
    }
};

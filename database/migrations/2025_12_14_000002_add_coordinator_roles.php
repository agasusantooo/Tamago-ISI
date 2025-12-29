<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        $now = now();

        // Ensure the two coordinator roles exist (idempotent)
        DB::table('roles')->updateOrInsert(
            ['name' => 'koordinator_tefa'],
            [
                'display_name' => 'Koordinator TEFA',
                'description' => 'Role untuk koordinator TEFA Fair',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'koordinator_story_conference'],
            [
                'display_name' => 'Koordinator Story Conference',
                'description' => 'Role untuk koordinator story conference',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        DB::table('roles')->where('name', 'koordinator_tefa')->delete();
        DB::table('roles')->where('name', 'koordinator_story_conference')->delete();
    }
};

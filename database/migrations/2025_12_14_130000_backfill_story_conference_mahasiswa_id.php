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
        // idempotent backfill: for each story_conference row missing mahasiswa_id,
        // try to find the mahasiswa by nim and set mahasiswa_id = mahasiswa.user_id
        DB::table('story_conference')
            ->whereNull('mahasiswa_id')
            ->whereNotNull('mahasiswa_nim')
            ->orderBy('id_conference')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $r) {
                    $nim = $r->mahasiswa_nim;
                    if (! $nim) {
                        continue;
                    }

                    $m = DB::table('mahasiswa')->where('nim', $nim)->first();
                    if ($m && ! empty($m->user_id)) {
                        DB::table('story_conference')
                            ->where('id_conference', $r->id_conference)
                            ->update(['mahasiswa_id' => $m->user_id]);
                    }
                }
            }, 'id_conference');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // reverse the backfill: set mahasiswa_id to null where it comes from a matching mahasiswa.user_id
        DB::table('story_conference')
            ->whereNotNull('mahasiswa_nim')
            ->whereNotNull('mahasiswa_id')
            ->orderBy('id_conference')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $r) {
                    $nim = $r->mahasiswa_nim;
                    if (! $nim) {
                        continue;
                    }
                    $m = DB::table('mahasiswa')->where('nim', $nim)->first();
                    if ($m && $m->user_id == $r->mahasiswa_id) {
                        DB::table('story_conference')
                            ->where('id_conference', $r->id_conference)
                            ->update(['mahasiswa_id' => null]);
                    }
                }
            }, 'id_conference');
    }
};

<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

it('backfills mahasiswa_id from mahasiswa_nim', function () {
    // prepare user and mahasiswa
    $user = User::factory()->create();

    DB::table('mahasiswa')->insert([
        'user_id' => $user->id,
        'nim' => 'TESTNIM123',
        'nama' => 'Test Student',
        'email' => 'test@student.example',
        'status' => 'active',
    ]);

    // ensure we have a proposal referenced by the story_conference row
    $proposalId = DB::table('proposals')->insertGetId([
        'judul' => 'Dummy Proposal',
        'deskripsi' => 'Dummy',
        'mahasiswa_nim' => 'TESTNIM123',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // story conference row with mahasiswa_nim and no mahasiswa_id
    $id = DB::table('story_conference')->insertGetId([
        'mahasiswa_nim' => 'TESTNIM123',
        'proposal_id' => $proposalId,
        'tanggal' => now(),
        'status' => 'menunggu_persetujuan',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // run the backfill logic (idempotent) - mirrors the migration
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

    $row = DB::table('story_conference')->where('id_conference', $id)->first();

    expect($row->mahasiswa_id)->toBe($user->id);
});

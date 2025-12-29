<?php

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\StoryConference;
use App\Models\User;

it('shows only story conference items on story conference dashboard', function () {
    $role = Role::firstOrCreate(['name' => 'koordinator_story_conference'], ['display_name' => 'Koordinator Story Conference']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $mahasiswa = Mahasiswa::factory()->create();

    // story conference pending (create a proposal first to satisfy NOT NULL)
    $proposal = \App\Models\Proposal::create([
        'mahasiswa_nim' => $mahasiswa->nim,
        'judul' => 'Test Proposal',
        'deskripsi' => 'Deskripsi',
        'status' => 'diajukan',
    ]);

    StoryConference::create([
        'mahasiswa_id' => $user->id,
        'mahasiswa_nim' => $mahasiswa->nim,
        'proposal_id' => $proposal->id,
        'judul_karya' => 'Test Story',
        'status' => 'menunggu_persetujuan',
        'tanggal_daftar' => now(),
    ]);

    // other non-story items that should NOT appear (we don't need to create TEFA here)

    $response = $this->actingAs($user)->get(route('koordinator_story_conference.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Pendaftaran Story Conference');
    $response->assertDontSee('Pendaftaran TEFA');
    $response->assertDontSee('Persetujuan Pengajuan Ujian');
});

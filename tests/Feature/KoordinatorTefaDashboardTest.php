<?php

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\StoryConference;
use App\Models\TefaFair;
use App\Models\User;

it('shows only tefa items on tefa dashboard', function () {
    $role = Role::firstOrCreate(['name' => 'koordinator_tefa'], ['display_name' => 'Koordinator TEFA']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $mahasiswa = Mahasiswa::factory()->create();

    $proposal = \App\Models\Proposal::create([
        'mahasiswa_nim' => $mahasiswa->nim,
        'judul' => 'Test Proposal',
        'deskripsi' => 'Deskripsi',
        'status' => 'diajukan',
    ]);

    TefaFair::create([
        'proposal_id' => $proposal->id,
        'mahasiswa_nim' => $mahasiswa->nim,
        'semester' => '2025/2026',
        'status' => 'menunggu_review',
    ]);

    StoryConference::create([
        'mahasiswa_id' => $user->id,
        'mahasiswa_nim' => $mahasiswa->nim,
        'proposal_id' => $proposal->id,
        'judul_karya' => 'Test Story',
        'status' => 'menunggu_persetujuan',
        'tanggal_daftar' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('koordinator_tefa.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Pendaftaran TEFA');
    $response->assertDontSee('Pendaftaran Story Conference');
});

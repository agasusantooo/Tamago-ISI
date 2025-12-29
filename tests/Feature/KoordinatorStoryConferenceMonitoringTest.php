<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\StoryConference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KoordinatorStoryConferenceMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_sets_status_diterima_and_tanggal_review()
    {
        $role = Role::firstOrCreate(['name' => 'koordinator_story_conference'], ['display_name' => 'Koordinator Story Conference']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $mahasiswa = Mahasiswa::factory()->create();
        $proposal = Proposal::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'judul' => 'Test Proposal',
            'deskripsi' => 'Deskripsi proposal test',
            'status' => 'diajukan',
        ]);

        $registration = StoryConference::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'proposal_id' => $proposal->id,
            'status' => 'menunggu_persetujuan',
        ]);

        $response = $this->actingAs($user)->post(route('koordinator_story_conference.monitoring.approve', $registration->{$registration->getKeyName()}));

        $response->assertRedirect(route('koordinator_story_conference.monitoring'));
        $this->assertDatabaseHas('story_conference', ['id_conference' => $registration->id_conference ?? $registration->id, 'status' => 'diterima']);
    }

    public function test_reject_stores_reason_and_sets_status()
    {
        $role = Role::firstOrCreate(['name' => 'koordinator_story_conference'], ['display_name' => 'Koordinator Story Conference']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $mahasiswa = Mahasiswa::factory()->create();
        $proposal = Proposal::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'judul' => 'Test Proposal',
            'deskripsi' => 'Deskripsi proposal test',
            'status' => 'diajukan',
        ]);

        $registration = StoryConference::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'proposal_id' => $proposal->id,
            'status' => 'menunggu_persetujuan',
        ]);

        $response = $this->actingAs($user)->post(route('koordinator_story_conference.monitoring.reject', $registration->{$registration->getKeyName()}), ['reason' => 'Alasan penolakan valid']);

        $response->assertRedirect(route('koordinator_story_conference.monitoring'));
        $this->assertDatabaseHas('story_conference', ['id_conference' => $registration->id_conference ?? $registration->id, 'status' => 'ditolak', 'catatan_panitia' => 'Alasan penolakan valid']);
    }
}

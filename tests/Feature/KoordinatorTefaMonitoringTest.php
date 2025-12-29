<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\TefaFair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KoordinatorTefaMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_changes_status_to_disetujui()
    {
        $role = Role::firstOrCreate(['name' => 'koordinator_tefa'], ['display_name' => 'Koordinator TEFA']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $registration = TefaFair::create([
            'mahasiswa_nim' => '12345',
            'judul_karya' => 'Contoh Karya',
            'semester' => '2025/01',
            'status' => 'menunggu_review',
        ]);

        $response = $this->actingAs($user)->post(route('koordinator_tefa.monitoring.approve', $registration->{$registration->getKeyName()}));

        $response->assertRedirect(route('koordinator_tefa.monitoring'));
        $this->assertDatabaseHas('tefa_fair', ['id_tefa' => $registration->id_tefa ?? $registration->id, 'status' => 'disetujui']);
    }

    public function test_reject_requires_reason_min_length()
    {
        $role = Role::firstOrCreate(['name' => 'koordinator_tefa'], ['display_name' => 'Koordinator TEFA']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $registration = TefaFair::create([
            'mahasiswa_nim' => '12345',
            'judul_karya' => 'Contoh Karya',
            'semester' => '2025/01',
            'status' => 'menunggu_review',
        ]);

        $response = $this->actingAs($user)->post(route('koordinator_tefa.monitoring.reject', $registration->{$registration->getKeyName()}), ['reason' => 'ok']);

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('tefa_fair', ['id_tefa' => $registration->id_tefa ?? $registration->id, 'status' => 'menunggu_review']);
    }

    public function test_reject_with_reason_changes_status_to_ditolak()
    {
        $role = Role::firstOrCreate(['name' => 'koordinator_tefa'], ['display_name' => 'Koordinator TEFA']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $registration = TefaFair::create([
            'mahasiswa_nim' => '12345',
            'judul_karya' => 'Contoh Karya',
            'semester' => '2025/01',
            'status' => 'menunggu_review',
        ]);

        $response = $this->actingAs($user)->post(route('koordinator_tefa.monitoring.reject', $registration->{$registration->getKeyName()}), ['reason' => 'Alasan penolakan valid']);

        $response->assertRedirect(route('koordinator_tefa.monitoring'));
        $this->assertDatabaseHas('tefa_fair', ['id_tefa' => $registration->id_tefa ?? $registration->id, 'status' => 'ditolak']);
    }
}

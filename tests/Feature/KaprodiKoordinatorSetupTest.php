<?php

use App\Models\Dosen;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows both tefa and story coordinator setup and can update them', function () {

    $roleKaprodi = Role::firstOrCreate(['name' => 'kaprodi'], ['display_name' => 'Kaprodi']);
    $roleTefa = Role::firstOrCreate(['name' => 'koordinator_tefa'], ['display_name' => 'Koordinator TEFA']);
    $roleStory = Role::firstOrCreate(['name' => 'koordinator_story_conference'], ['display_name' => 'Koordinator Story Conference']);

    $kaprodi = User::factory()->create(['role_id' => $roleKaprodi->id]);

    // Create two lecturers (users + dosen records)
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $dosenA = Dosen::create(['nidn' => '1000', 'user_id' => $userA->id, 'nama' => $userA->name, 'jabatan' => 'Dosen', 'rumpun_ilmu' => 'Umum']);
    $dosenB = Dosen::create(['nidn' => '1001', 'user_id' => $userB->id, 'nama' => $userB->name, 'jabatan' => 'Dosen', 'rumpun_ilmu' => 'Umum']);

    // Visit the page
    $response = $this->actingAs($kaprodi)->get(route('kaprodi.koordinator-tefa'));
    $response->assertStatus(200);
    $response->assertSee('Koordinator TEFA');
    $response->assertSee('Koordinator Story Conference');

    // Update TEFA coordinator
    $this->actingAs($kaprodi)->post(route('kaprodi.koordinator-tefa.update'), ['user_id' => $dosenA->user->id]);
    $this->assertDatabaseHas('users', ['id' => $dosenA->user->id, 'role_id' => $roleTefa->id]);

    // Update Story Conference coordinator
    $this->actingAs($kaprodi)->post(route('kaprodi.koordinator-story-conference.update'), ['user_id' => $dosenB->user->id]);
    $this->assertDatabaseHas('users', ['id' => $dosenB->user->id, 'role_id' => $roleStory->id]);
});

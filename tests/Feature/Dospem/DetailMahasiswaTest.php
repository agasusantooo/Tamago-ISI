<?php

use App\Models\Role;
use App\Models\User;

it('allows dospem to view mahasiswa detail (feature)', function () {
	// Ensure role exists
	$role = Role::firstOrCreate(['name' => 'dospem'], ['display_name' => 'Dosen Pembimbing']);

	// Create a user with dospem role
	$user = User::factory()->create([
        'role_id' => $role->id,
        'email' => 'dospem-test@example.com',
        'password' => bcrypt('password'),
    ]);	$this->actingAs($user);

	// Create a dummy mahasiswa record so controller can find it (use mahasiswa table seeder or factory if available)
	// We'll create a minimal mahasiswa via DB insert to avoid relying on factory presence.
	$mahasiswaId = null;
	if (!\Illuminate\Support\Facades\Schema::hasTable('mahasiswa')) {
		$this->markTestSkipped('mahasiswa table missing');
	}

	// Insert a mahasiswa row if not present
	\DB::table('mahasiswa')->insert([
		'nim' => 'TESTNIM001',
		'nama' => 'Test Mahasiswa',
		'email' => 'mahasiswa-test@example.com',
		'status' => 'aktif',
		'created_at' => now(),
		'updated_at' => now(),
	]);

	$response = $this->get(route('dospem.mahasiswa-bimbingan.show', ['id' => 'TESTNIM001']));

	$response->assertStatus(200);
	$response->assertViewIs('dospem.detail-mahasiswa');
	$response->assertSee('Detail Mahasiswa');
});


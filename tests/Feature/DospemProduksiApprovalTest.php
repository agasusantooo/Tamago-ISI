<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Produksi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DospemProduksiApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Ensure roles exist
        Role::updateOrCreate(['name' => 'dospem'], ['display_name' => 'Dosen Pembimbing']);
        Role::updateOrCreate(['name' => 'mahasiswa'], ['display_name' => 'Mahasiswa']);
    }

    public function test_dospem_can_approve_pra_produksi_when_linked()
    {
        // Create dospem user and Dosen record
        $roleDospem = Role::where('name', 'dospem')->first();
        $dospemUser = User::factory()->create(['role_id' => $roleDospem->id]);
        $dosen = Dosen::updateOrCreate(
            ['nidn' => '99999999'],
            [
                'user_id' => $dospemUser->id,
                'nama' => 'Test Dospem',
                'jabatan' => 'Dosen',
                'rumpun_ilmu' => 'fotografi',
                'status' => 'aktif',
            ]
        );

        // Create mahasiswa user and record linked to the dosen via nidn
        $roleMhs = Role::where('name', 'mahasiswa')->first();
        $mhsUser = User::factory()->create(['role_id' => $roleMhs->id]);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $mhsUser->id,
            'nim' => '99990001',
            'nama' => 'Test Mahasiswa',
            'email' => 'mhs-test1@example.com',
            'dosen_pembimbing_id' => $dosen->nidn,
            'status' => 'aktif',
        ]);

        // Create a minimal Proposal required by tim_produksi
        $proposal = \App\Models\Proposal::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'dosen_id' => $dosen->nidn,
            'judul' => 'Test Proposal',
            'deskripsi' => 'Deskripsi singkat',
            'status' => 'diajukan',
        ]);

        // Create produksi record
        $produksi = Produksi::create([
            'mahasiswa_id' => $mhsUser->id,
            'proposal_id' => $proposal->id,
            'dosen_id' => $dosen->nidn,
            'status_pra_produksi' => 'menunggu_review',
        ]);

        // Act as dospem user and post approval
        $response = $this->actingAs($dospemUser)->postJson(route('dospem.produksi.pra-produksi', ['id' => $produksi->id]), [
            'produksi_status' => 'disetujui',
            'produksi_feedback' => 'Bagus',
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('tim_produksi', [
            'id' => $produksi->id,
            'status_pra_produksi' => 'disetujui',
        ]);
    }

    public function test_dospem_cannot_approve_if_not_linked()
    {
        // Create a dospem user but do NOT link it to a Dosen record
        $roleDospem = Role::where('name', 'dospem')->first();
        $dospemUser = User::factory()->create(['role_id' => $roleDospem->id]);

        // Create another dosen and mahasiswa linked to that other dosen
        $otherDosen = Dosen::updateOrCreate(
            ['nidn' => '88888888'],
            [
                'user_id' => null,
                'nama' => 'Other Dosen',
                'jabatan' => 'Dosen',
                'rumpun_ilmu' => 'fotografi',
                'status' => 'aktif',
            ]
        );
        $roleMhs = Role::where('name', 'mahasiswa')->first();
        $mhsUser = User::factory()->create(['role_id' => $roleMhs->id]);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $mhsUser->id,
            'nim' => '99990002',
            'nama' => 'Test Mahasiswa 2',
            'email' => 'mhs-test2@example.com',
            'dosen_pembimbing_id' => $otherDosen->nidn,
            'status' => 'aktif',
        ]);

        $proposal2 = \App\Models\Proposal::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'dosen_id' => $otherDosen->nidn,
            'judul' => 'Test Proposal 2',
            'deskripsi' => 'Deskripsi singkat 2',
            'status' => 'diajukan',
        ]);

        $produksi = Produksi::create([
            'mahasiswa_id' => $mhsUser->id,
            'proposal_id' => $proposal2->id,
            'dosen_id' => $otherDosen->nidn,
            'status_pra_produksi' => 'menunggu_review',
        ]);

        $response = $this->actingAs($dospemUser)->postJson(route('dospem.produksi.pra-produksi', ['id' => $produksi->id]), [
            'produksi_status' => 'disetujui',
            'produksi_feedback' => 'OK',
        ]);

        $response->assertStatus(403)->assertJson(['status' => 'error']);
    }

    public function test_dospem_without_dosen_but_pembimbing_with_generated_nidn_can_approve()
    {
        $roleDospem = Role::where('name', 'dospem')->first();
        $dospemUser = User::factory()->create(['role_id' => $roleDospem->id]);

        // Expected generated nidn based on ensureDosenForAuth implementation
        $expectedNidn = '9'.str_pad($dospemUser->id, 7, '0', STR_PAD_LEFT);

        $roleMhs = Role::where('name', 'mahasiswa')->first();
        $mhsUser = User::factory()->create(['role_id' => $roleMhs->id]);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $mhsUser->id,
            'nim' => '99990003',
            'nama' => 'Test Mahasiswa 3',
            'email' => 'mhs-test3@example.com',
            'dosen_pembimbing_id' => $expectedNidn,
            'status' => 'aktif',
        ]);

        // Create a placeholder Dosen row with the expected nidn so the FK constraint for tim_produksi is satisfied.
        Dosen::updateOrCreate([
            'nidn' => $expectedNidn,
        ], [
            'user_id' => null,
            'nama' => 'Placeholder Dosen',
            'jabatan' => 'Dosen',
            'rumpun_ilmu' => 'fotografi',
            'status' => 'aktif',
        ]);

        $proposal = \App\Models\Proposal::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'dosen_id' => null,
            'judul' => 'Test Proposal 3',
            'deskripsi' => 'Deskripsi singkat 3',
            'status' => 'diajukan',
        ]);

        $produksi = Produksi::create([
            'mahasiswa_id' => $mhsUser->id,
            'proposal_id' => $proposal->id,
            'dosen_id' => $expectedNidn,
            'status_pra_produksi' => 'menunggu_review',
        ]);

        $response = $this->actingAs($dospemUser)->postJson(route('dospem.produksi.pra-produksi', ['id' => $produksi->id]), [
            'produksi_status' => 'disetujui',
            'produksi_feedback' => 'OK',
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('dosen', ['user_id' => $dospemUser->id, 'nidn' => $expectedNidn]);
        $this->assertDatabaseHas('tim_produksi', ['id' => $produksi->id, 'status_pra_produksi' => 'disetujui']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MahasiswaProposalTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Role::updateOrCreate(['name' => 'mahasiswa'], ['display_name' => 'Mahasiswa']);
    }

    public function test_mahasiswa_without_bimbingan_can_view_create_form()
    {
        $role = Role::where('name', 'mahasiswa')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12020001',
            'nama' => 'Test Student',
            'email' => 'student@example.com',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get(route('mahasiswa.proposal.create'));
        $response->assertStatus(200);
        $response->assertSee('Form Pengajuan Proposal');
    }

    public function test_mahasiswa_without_bimbingan_can_submit_proposal()
    {
        Storage::fake('public');

        $role = Role::where('name', 'mahasiswa')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12020002',
            'nama' => 'Test Student 2',
            'email' => 'student2@example.com',
            'status' => 'aktif',
        ]);

        $file = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post(route('mahasiswa.proposal.store'), [
            'judul' => 'Proposal Tanpa Bimbingan',
            'deskripsi' => str_repeat('a', 120),
            'rumpun_ilmu' => 'Fotografi',
            'file_proposal' => $file,
            'dosen_id' => null,
        ]);

        $response->assertRedirect(route('mahasiswa.proposal.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('proposals', [
            'mahasiswa_nim' => $mahasiswa->nim,
            'judul' => 'Proposal Tanpa Bimbingan',
            'status' => 'diajukan',
        ]);

        // Check file persisted to storage
        $proposal = \App\Models\Proposal::where('mahasiswa_nim', $mahasiswa->nim)->first();
        $this->assertNotNull($proposal->file_proposal);
        Storage::disk('public')->assertExists($proposal->file_proposal);
    }

    public function test_mahasiswa_can_select_dosen_and_it_shows_in_detail()
    {
        Storage::fake('public');

        $role = Role::where('name', 'mahasiswa')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12020003',
            'nama' => 'Test Student 3',
            'email' => 'student3@example.com',
            'status' => 'aktif',
        ]);

        // Create a dosen with nidn
        \App\Models\Dosen::create([
            'nidn' => '12345678',
            'nama' => 'Dosen Pembimbing A',
            'jabatan' => 'Dosen',
            'rumpun_ilmu' => 'fotografi',
            'status' => 'aktif',
        ]);

        $file = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post(route('mahasiswa.proposal.store'), [
            'judul' => 'Proposal Dengan Dosen',
            'deskripsi' => str_repeat('a', 120),
            'rumpun_ilmu' => 'Animasi',
            'file_proposal' => $file,
            'dosen_id' => '12345678',
        ]);

        $this->assertDatabaseHas('proposals', [
            'mahasiswa_nim' => '12020003',
            'judul' => 'Proposal Dengan Dosen',
            'dosen_id' => '12345678',
        ]);

        $proposal = \App\Models\Proposal::where('mahasiswa_nim', '12020003')->first();
        $this->assertNotNull($proposal);

        $show = $this->actingAs($user)->get(route('mahasiswa.proposal.show', $proposal->id));
        $show->assertStatus(200);
        $show->assertSee('Dosen Pembimbing');
        $show->assertSee('Dosen Pembimbing A');
    }

    public function test_show_displays_dosen_id_when_record_missing()
    {
        Storage::fake('public');

        $role = Role::where('name', 'mahasiswa')->first();
        $user = User::factory()->create(['role_id' => $role->id]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12020004',
            'nama' => 'Test Student 4',
            'email' => 'student4@example.com',
            'status' => 'aktif',
        ]);

        $file = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Create a dosen first then delete it to simulate a proposal pointing to a missing record
        \App\Models\Dosen::create([
            'nidn' => 'NIDN_NOT_EXISTS',
            'nama' => 'Temp Dosen',
            'jabatan' => 'Dosen',
            'rumpun_ilmu' => 'fotografi',
            'status' => 'aktif',
        ]);

        // Create proposal referencing that dosen
        \App\Models\Proposal::create([
            'mahasiswa_nim' => '12020004',
            'dosen_id' => 'NIDN_NOT_EXISTS',
            'judul' => 'Proposal Dosen Tidak Ada',
            'deskripsi' => str_repeat('a', 120),
            'rumpun_ilmu' => 'Fotografi',
            'file_proposal' => 'proposals/12020004/fake.pdf',
            'versi' => 1,
            'status' => 'diajukan',
            'tanggal_pengajuan' => now(),
        ]);

        // Delete the dosen to simulate a missing referenced Dosen record
        \App\Models\Dosen::where('nidn', 'NIDN_NOT_EXISTS')->delete();

        $proposal = \App\Models\Proposal::where('mahasiswa_nim', '12020004')->first();
        $this->assertNotNull($proposal);
        $this->assertEquals('NIDN_NOT_EXISTS', $proposal->dosen_id, 'Expected proposal to keep the deleted dosen nidn');

        $show = $this->actingAs($user)->get(route('mahasiswa.proposal.show', $proposal->id));
        $show->assertStatus(200);
        $show->assertSee('Dosen Pembimbing');
        $show->assertSee('Dosen yang diajukan: NIDN_NOT_EXISTS');
    }
}

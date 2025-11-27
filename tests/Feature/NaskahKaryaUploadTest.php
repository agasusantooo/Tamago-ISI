<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;

class NaskahKaryaUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mahasiswa_can_upload_naskah_and_it_is_stored()
    {
        Storage::fake('public');

        // create a user and related mahasiswa record
        $user = User::factory()->create();
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => 'TEST123',
            'nama' => 'Test Mahasiswa',
        ]);

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('naskah.pdf', 1000, 'application/pdf');

        $response = $this->post(route('mahasiswa.naskah-karya.upload'), [
            'file_naskah' => $file,
            'link_jurnal' => 'https://example.com/article/1'
        ]);

        $response->assertRedirect(route('mahasiswa.naskah-karya'));

        // ensure a file exists under the student's naskah directory
        $files = Storage::disk('public')->files('naskah/' . $mahasiswa->nim);
        $this->assertNotEmpty($files, 'Expected at least one file in naskah/' . $mahasiswa->nim);
    }
}

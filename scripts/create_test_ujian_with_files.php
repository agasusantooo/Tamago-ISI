<?php
// scripts/create_test_ujian_with_files.php
// Usage: php scripts/create_test_ujian_with_files.php [user_id]
$uid = $argv[1] ?? 5;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ProjekAkhir;
use App\Models\UjianTA;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

try {
    $user = User::find($uid);
    if (!$user) {
        echo "User $uid not found\n";
        exit(1);
    }

    $mahasiswa = $user->mahasiswa;
    if (!$mahasiswa) {
        echo "Mahasiswa relation not found for user $uid\n";
        exit(1);
    }

    // Ensure approved proposal exists
    $proposal = \App\Models\Proposal::where('mahasiswa_nim', $mahasiswa->nim)->where('status', 'disetujui')->latest()->first();
    if (!$proposal) {
        echo "No approved proposal found for nim {$mahasiswa->nim}\n";
        exit(1);
    }

    // Create projek_akhir if missing
    $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
    if (!$projek) {
        $projek = ProjekAkhir::create([
            'nim' => $mahasiswa->nim,
            'judul' => $proposal->judul ?? 'Auto Judul',
            'file_proposal' => $proposal->file_proposal ?? null,
            'status' => 'aktif',
        ]);
        echo "Created ProjekAkhir id={$projek->id_proyek_akhir}\n";
    } else {
        echo "Using ProjekAkhir id={$projek->id_proyek_akhir}\n";
    }

    // Create temp files
    $tmpDir = sys_get_temp_dir();
    $suratPath = tempnam($tmpDir, 'surat_') . '.pdf';
    $transPath = tempnam($tmpDir, 'trans_') . '.pdf';
    file_put_contents($suratPath, "%PDF-1.4 Dummy PDF content\n");
    file_put_contents($transPath, "%PDF-1.4 Dummy PDF content\n");

    // Ensure storage link exists (public disk)
    // Save files to public disk under ujian-ta/{userId}
    $suratName = 'surat_pengantar_' . time() . '.pdf';
    $transName = 'transkrip_' . time() . '.pdf';

    $suratPathStored = Storage::disk('public')->putFileAs('ujian-ta/' . $user->id, new \Illuminate\Http\File($suratPath), $suratName);
    $transPathStored = Storage::disk('public')->putFileAs('ujian-ta/' . $user->id, new \Illuminate\Http\File($transPath), $transName);

    echo "Stored surat: $suratPathStored\n";
    echo "Stored transkrip: $transPathStored\n";

    // Create ujian record using available columns
    $cols = Schema::getColumnListing('ujian_tugas_akhir');
    $data = [];
    if (in_array('id_proyek_akhir', $cols)) $data['id_proyek_akhir'] = $projek->id_proyek_akhir;
    if (in_array('file_surat_pengantar', $cols)) $data['file_surat_pengantar'] = $suratPathStored;
    if (in_array('file_transkrip_nilai', $cols)) $data['file_transkrip_nilai'] = $transPathStored;
    if (in_array('status_pendaftaran', $cols)) $data['status_pendaftaran'] = 'pengajuan_ujian';
    if (in_array('status_ujian', $cols)) $data['status_ujian'] = 'belum_ujian';
    if (in_array('tanggal_daftar', $cols)) $data['tanggal_daftar'] = now();

    $ujian = UjianTA::create($data);
    echo "Created UjianTA id=" . ($ujian->id_ujian ?? $ujian->getKey()) . "\n";

    // cleanup temp files
    @unlink($suratPath);
    @unlink($transPath);

    echo "Done.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

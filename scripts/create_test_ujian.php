<?php
// scripts/create_test_ujian.php
// Usage: php scripts/create_test_ujian.php [user_id]
$uid = $argv[1] ?? 5;
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ProjekAkhir;
use App\Models\UjianTA;
use App\Models\Proposal;
use Illuminate\Support\Facades\Schema;

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

    // Ensure there's an approved proposal
    $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
        ->where('status', 'disetujui')
        ->latest()
        ->first();

    if (!$proposal) {
        echo "No approved proposal for nim {$mahasiswa->nim}. Please approve a proposal first.\n";
        exit(1);
    }

    // Create projek_akhir if missing
    $projek = ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first();
    if (!$projek) {
        $projek = ProjekAkhir::create([
            'nim' => $mahasiswa->nim,
            'nidn1' => null,
            'nidn2' => null,
            'judul' => $proposal->judul ?? 'Judul Projek (auto)',
            'file_proposal' => $proposal->file_proposal ?? null,
            'status' => 'aktif',
        ]);
        echo "Created ProjekAkhir id={$projek->id_proyek_akhir}\n";
    } else {
        echo "Using existing ProjekAkhir id={$projek->id_proyek_akhir}\n";
    }

    // Create ujian if missing. Use only columns that actually exist in DB to avoid migration mismatch.
    $ujian = UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first();
    if (!$ujian) {
        $cols = Schema::getColumnListing('ujian_tugas_akhir');
        $data = [];
        if (in_array('id_proyek_akhir', $cols)) $data['id_proyek_akhir'] = $projek->id_proyek_akhir;
        if (in_array('status_pendaftaran', $cols)) $data['status_pendaftaran'] = 'jadwal_ditetapkan';
        if (in_array('status_ujian', $cols)) $data['status_ujian'] = 'selesai_ujian';
        if (in_array('tanggal_daftar', $cols)) $data['tanggal_daftar'] = now();
        if (in_array('tanggal_ujian', $cols)) $data['tanggal_ujian'] = now();
        if (in_array('nilai_akhir', $cols)) $data['nilai_akhir'] = 85;

        // create using only available columns
        $ujian = UjianTA::create($data);
        echo "Created UjianTA id=" . ($ujian->id_ujian ?? $ujian->getKey()) . " (status_ujian=" . ($ujian->status_ujian ?? 'n/a') . ")\n";
    } else {
        echo "Existing UjianTA id={$ujian->id_ujian} (status_ujian={$ujian->status_ujian})\n";
        if (Schema::hasColumn('ujian_tugas_akhir', 'status_ujian') && $ujian->status_ujian !== 'selesai_ujian') {
            $ujian->update(['status_ujian' => 'selesai_ujian']);
            echo "Updated UjianTA id={$ujian->id_ujian} to status_ujian=selesai_ujian\n";
        }
    }

    echo "Done. You can now visit the hasil page for this mahasiswa.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}


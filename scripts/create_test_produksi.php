<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produksi;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Mahasiswa;

// Usage: php scripts/create_test_produksi.php <user_id> [proposal_id]
// Example: php scripts/create_test_produksi.php 84 2

$argv0 = isset($argv) ? $argv : [];
if (count($argv0) < 2) {
    echo "Usage: php scripts/create_test_produksi.php <user_id> [proposal_id]\n";
    exit(1);
}

$userId = intval($argv0[1]);
$proposalId = isset($argv0[2]) ? intval($argv0[2]) : null;

$user = User::find($userId);
if (!$user) {
    echo "User with id={$userId} not found.\n";
    exit(1);
}

// try to find a proposal if not provided
if (!$proposalId) {
    // try match proposal by mahasiswa->nim
    $mahasiswa = $user->mahasiswa ?? null;
    if ($mahasiswa) {
        $proposal = Proposal::where('mahasiswa_nim', $mahasiswa->nim)->where('status', 'disetujui')->latest()->first();
        if ($proposal) $proposalId = $proposal->id;
    }
}

if (!$proposalId) {
    echo "No proposal id provided and none found for user. Please pass a valid proposal_id.\n";
    exit(1);
}

$proposal = Proposal::find($proposalId);
if (!$proposal) {
    echo "Proposal id={$proposalId} not found.\n";
    exit(1);
}

// Check if produksi already exists for this user+proposal
$existing = Produksi::where('mahasiswa_id', $userId)->where('proposal_id', $proposalId)->first();
if ($existing) {
    echo "Produksi already exists: ID={$existing->id} | mahasiswa_id={$existing->mahasiswa_id} | proposal_id={$existing->proposal_id}\n";
    exit(0);
}

$now = date('Y-m-d H:i:s');
$produksi = Produksi::create([
    'mahasiswa_id' => $userId,
    'proposal_id' => $proposalId,
    'dosen_id' => $proposal->dosen_id ?? null,
    'file_skenario' => null,
    'file_storyboard' => null,
    'file_dokumen_pendukung' => null,
    'file_produksi' => null,
    'status_pra_produksi' => 'disetujui',
    'status_produksi' => 'menunggu_review',
    'status_pasca_produksi' => 'belum_upload',
    'tanggal_upload_pra' => $now,
]);

if ($produksi) {
    echo "Created produksi: ID={$produksi->id} | mahasiswa_id={$produksi->mahasiswa_id} | proposal_id={$produksi->proposal_id}\n";
} else {
    echo "Failed to create produksi.\n";
}

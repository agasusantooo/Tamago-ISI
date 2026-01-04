<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UjianTA;
use App\Models\Dosen;
use App\Models\User;

$uid = $argv[1] ?? 5;
$user = User::find($uid);
if (! $user) { echo "User $uid not found\n"; exit(1); }
$dosen = Dosen::where('user_id', $user->id)->first();
$dosenNidn = $dosen?->nidn ?? null;

$ujianTA = UjianTA::where(function ($q) use ($user, $dosenNidn) {
    $q->where('ketua_penguji_id', $user->id)
      ->orWhere('penguji_ahli_id', $user->id)
      ->orWhere('dosen_pembimbing_id', $dosenNidn)
      ->orWhere('dosen_pembimbing_id', $user->id);
})->orderBy('tanggal_ujian', 'asc')->get();

$extra = UjianTA::where(function ($q) use ($dosenNidn, $user) {
    $q->whereHas('mahasiswa', function ($qq) use ($dosenNidn, $user) {
        $qq->where('dosen_pembimbing_id', $dosenNidn)->orWhere('dosen_pembimbing_id', $user->id);
    })->orWhereHas('projekAkhir', function ($qq) use ($dosenNidn, $user) {
        $qq->whereHas('mahasiswa', function ($qqq) use ($dosenNidn, $user) {
            $qqq->where('dosen_pembimbing_id', $dosenNidn)->orWhere('dosen_pembimbing_id', $user->id);
        });
    });
})->get();

$pending = UjianTA::where('status_pendaftaran', 'pengajuan_ujian')->orderBy('tanggal_ujian', 'asc')->get();

$all = $ujianTA->merge($extra)->merge($pending)->unique('id_ujian')->values();

echo "User {$user->id} ({$user->name}) => found ".count($all)." ujian entries\n";
foreach ($all as $u) {
    echo "- id={$u->id_ujian}, projek=".($u->id_proyek_akhir ?? 'NULL').", status_pendaftaran={$u->status_pendaftaran}, tanggal={$u->tanggal_ujian}\n";
}

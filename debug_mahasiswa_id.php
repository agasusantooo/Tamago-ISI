<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;

$mahasiswa = Mahasiswa::first();

if ($mahasiswa) {
    echo "Mahasiswa data:\n";
    echo "- ID: " . ($mahasiswa->id ?? 'NULL') . "\n";
    echo "- NIM: " . ($mahasiswa->nim ?? 'NULL') . "\n";
    echo "- User ID: " . ($mahasiswa->user_id ?? 'NULL') . "\n";
    echo "- Name: " . ($mahasiswa->name ?? 'NULL') . "\n";
} else {
    echo "No mahasiswa found\n";
}

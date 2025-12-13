#!/bin/bash
cd /d/C/Tamago-ISI

# Get list of users and their mahasiswa data
php artisan tinker << 'EOF'
$users = \App\Models\User::where('email', 'like', '%bene%')->orWhere('name', 'like', '%bene%')->get();
echo "=== Users matching 'bene' ===\n";
foreach ($users as $u) {
    $mahasiswa = $u->mahasiswa;
    echo "User: {$u->id} | Name: {$u->name} | Email: {$u->email} | Mahasiswa: " . ($mahasiswa ? $mahasiswa->id . " ({$mahasiswa->nim})" : "NULL") . "\n";
    
    if ($mahasiswa) {
        $produksi = \App\Models\Produksi::where('mahasiswa_id', $u->id)->count();
        echo "  -> Produksi records: $produksi\n";
    }
}

echo "\n=== Total Produksi in DB ===\n";
echo "Total: " . \App\Models\Produksi::count() . "\n";
EOF

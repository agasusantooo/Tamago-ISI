<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\User;
use App\Models\Produksi;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    public function bimbingan()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        echo "=== Debug Bimbingan ===\n";
        echo "User ID: " . $user->id . "\n";
        echo "User Email: " . $user->email . "\n";
        echo "Mahasiswa: " . ($mahasiswa ? $mahasiswa->nim . " (id={$mahasiswa->id})" : "NULL") . "\n\n";

        // Check query dengan WHERE mahasiswa_id
        $query = Bimbingan::where('mahasiswa_id', $mahasiswa->id ?? 0);
        echo "Query: WHERE mahasiswa_id = " . ($mahasiswa->id ?? 'NULL') . "\n";
        echo "Count: " . $query->count() . "\n\n";

        if ($query->count() > 0) {
            echo "Data (seharusnya hanya user ini):\n";
            foreach ($query->get() as $b) {
                echo "- ID: {$b->id_bimbingan} | nim: {$b->nim} | mahasiswa_id: {$b->mahasiswa_id} | status: {$b->status}\n";
            }
        }

        echo "\n=== ALL Distinct mahasiswa_id ===\n";
        $totals = Bimbingan::groupBy('mahasiswa_id')->selectRaw('mahasiswa_id, COUNT(*) as total')->orderBy('total', 'desc')->get();
        foreach ($totals as $t) {
            $mid = $t->mahasiswa_id ?? 'NULL';
            echo "mahasiswa_id: $mid => {$t->total} records\n";
        }
    }

    public function produksi()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        echo "=== Debug Produksi untuk user " . $user->email . " ===\n";
        echo "User ID: " . $user->id . "\n";
        echo "Mahasiswa ID: " . ($mahasiswa?->id ?? 'NULL') . " (NIM: " . ($mahasiswa?->nim ?? 'NULL') . ")\n\n";

        if ($mahasiswa) {
            echo "Produksi untuk user ini (mahasiswa_id = {$mahasiswa->id}):\n";
            $produksis = Produksi::where('mahasiswa_id', $mahasiswa->id)->get();
            echo "Count: " . $produksis->count() . "\n\n";
            
            foreach ($produksis as $p) {
                echo "- ID: {$p->id} | proposal_id: {$p->proposal_id} | status: {$p->status_produksi}\n";
            }
        }

        echo "\n=== ALL Produksi in Database ===\n";
        $all = Produksi::all();
        echo "Total: " . $all->count() . "\n\n";
        foreach ($all->take(15) as $p) {
            echo "ID: {$p->id} | mahasiswa_id: {$p->mahasiswa_id} | proposal: {$p->proposal_id} | status: {$p->status_produksi}\n";
        }

        echo "\n=== ALL Users ===\n";
        $users = User::all();
        foreach ($users as $u) {
            echo "User: {$u->id} | {$u->name} | {$u->email}\n";
        }
    }
}

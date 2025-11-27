<?php

namespace App\Http\Controllers\KoordinatorTA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;

class KoordinatorTaskController extends Controller
{
    public function approve(Request $request, $id)
    {
        // Try to find proposal by primary key or alternative column
        $proposal = Proposal::find($id) ?: Proposal::where('id_proposal', $id)->first();

        if (! $proposal) {
            return back()->with('error', 'Tugas tidak ditemukan.');
        }

        $proposal->status = 'disetujui';
        $proposal->save();

        return back()->with('success', 'Tugas disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $proposal = Proposal::find($id) ?: Proposal::where('id_proposal', $id)->first();

        if (! $proposal) {
            return back()->with('error', 'Tugas tidak ditemukan.');
        }

        $proposal->status = 'ditolak';
        $proposal->save();

        return back()->with('success', 'Tugas ditolak.');
    }
}

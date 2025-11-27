<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;

class KaprodiTaskController extends Controller
{
    public function approve(Request $request, $id)
    {
        $proposal = Proposal::find($id) ?: Proposal::where('id_proposal', $id)->first();

        if (! $proposal) {
            return back()->with('error', 'Tugas tidak ditemukan.');
        }

        $proposal->status = 'disetujui';
        $proposal->save();

        return back()->with('success', 'Tugas disetujui oleh Kaprodi.');
    }

    public function reject(Request $request, $id)
    {
        $proposal = Proposal::find($id) ?: Proposal::where('id_proposal', $id)->first();

        if (! $proposal) {
            return back()->with('error', 'Tugas tidak ditemukan.');
        }

        $proposal->status = 'ditolak';
        $proposal->save();

        return back()->with('success', 'Tugas ditolak oleh Kaprodi.');
    }
}

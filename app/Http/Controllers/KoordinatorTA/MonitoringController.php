<?php

namespace App\Http\Controllers\KoordinatorTA;

use App\Http\Controllers\Controller;
use App\Models\ProjekAkhir;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all final projects with their related student and proposal
        $projekAkhirs = ProjekAkhir::with(['mahasiswa.user', 'proposal'])->get();

        return view('koordinator_ta.monitoring', compact('projekAkhirs'));
    }

    /**
     * Approve the specified resource in storage.
     */
    public function approve(Request $request, $id)
    {
        $projekAkhir = ProjekAkhir::findOrFail($id);
        
        // Logic to approve the project, e.g., update status
        // This is a placeholder for the actual approval logic.
        $projekAkhir->status = 'approved_by_koordinator'; // Example status
        $projekAkhir->save();

        return redirect()->route('koordinator_ta.monitoring')->with('success', 'Projek akhir berhasil disetujui.');
    }

    /**
     * Reject the specified resource in storage.
     */
    public function reject(Request $request, $id)
    {
        $projekAkhir = ProjekAkhir::findOrFail($id);

        // Logic to reject the project
        // This is a placeholder for the actual rejection logic.
        $projekAkhir->status = 'rejected_by_koordinator'; // Example status
        $projekAkhir->save();

        return redirect()->route('koordinator_ta.monitoring')->with('error', 'Projek akhir ditolak.');
    }
}

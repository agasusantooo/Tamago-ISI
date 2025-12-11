<?php

namespace App\Http\Controllers\KoordinatorTefa;

use App\Http\Controllers\Controller;
use App\Models\TefaFair;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $registrations = TefaFair::with('mahasiswa.user')->get();
        return view('koordinator_tefa.monitoring', compact('registrations'));
    }

    public function approve(Request $request, $id)
    {
        $registration = TefaFair::findOrFail($id);
        $registration->status = 'approved';
        $registration->save();
        return redirect()->route('koordinator_tefa.monitoring')->with('success', 'Pendaftaran TEFA Fair disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $registration = TefaFair::findOrFail($id);
        $registration->status = 'rejected';
        $registration->save();
        return redirect()->route('koordinator_tefa.monitoring')->with('error', 'Pendaftaran TEFA Fair ditolak.');
    }
}

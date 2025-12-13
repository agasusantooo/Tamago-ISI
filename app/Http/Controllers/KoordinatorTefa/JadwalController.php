<?php

namespace App\Http\Controllers\KoordinatorTefa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalAcara;
use Carbon\Carbon;

class JadwalController extends Controller
{
    protected $eventType = 'tefa';

    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        // There should only be one timeline entry for TEFA.
        // We use firstOrNew to get the existing one or create a new instance.
        $jadwal = JadwalAcara::firstOrNew(['type' => $this->eventType]);
        
        return view('koordinator_tefa.jadwal', compact('jadwal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        JadwalAcara::updateOrCreate(
            ['type' => $this->eventType],
            [
                'title' => $request->input('title'),
                'start' => $request->input('start'),
                'end' => $request->input('end'),
            ]
        );

        return redirect()->route('koordinator_tefa.jadwal.index')->with('success', 'Jadwal TEFA Fair berhasil disimpan.');
    }
}

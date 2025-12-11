<?php

namespace App\Http\Controllers\KoordinatorStoryConference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalAcara;
use Carbon\Carbon;

class JadwalController extends Controller
{
    protected $eventType = 'story_conference';

    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        // There should only be one timeline entry for Story Conference.
        $jadwal = JadwalAcara::firstOrNew(['type' => $this->eventType]);
        
        return view('koordinator_story_conference.jadwal', compact('jadwal'));
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

        return redirect()->route('koordinator_story_conference.jadwal.index')->with('success', 'Jadwal Story Conference berhasil disimpan.');
    }
}

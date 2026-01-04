<?php

namespace App\Http\Controllers\KoordinatorStoryConference;

use App\Http\Controllers\Controller;
use App\Models\JadwalAcara;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    protected $eventType = 'story_conference';

    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        // Use latest jadwal as the current active one, and fetch history for listing.
        $jadwal = JadwalAcara::where('type', $this->eventType)->orderBy('created_at', 'desc')->first();

        if (! $jadwal) {
            // If none exists yet, initialize an empty instance so the form still works.
            $jadwal = new JadwalAcara(['type' => $this->eventType]);
        }

        $history = JadwalAcara::where('type', $this->eventType)->orderBy('created_at', 'desc')->get();

        return view('koordinator_story_conference.jadwal', compact('jadwal', 'history'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request)
    {
        if (! auth()->user() || ! auth()->user()->hasRole('koordinator_story_conference')) {
            abort(403, 'Unauthorized');
        }

        $messages = [
            'title.required' => 'Judul jadwal wajib diisi.',
            'title.max' => 'Judul jadwal terlalu panjang (maks 255 karakter).',
            'start.required' => 'Tanggal/waktu mulai wajib diisi.',
            'end.required' => 'Tanggal/waktu selesai wajib diisi.',
            'end.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];

        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ], $messages);

        try {
            // Create a new history entry each time a jadwal is saved so we keep a record of past setups.
            JadwalAcara::create([
                'type' => $this->eventType,
                'title' => $request->input('title'),
                'start' => $request->input('start'),
                'end' => $request->input('end'),
            ]);

            // Optionally, we could keep only the latest N records or implement versioning later.

            \Log::info('StoryConference jadwal updated', ['by' => auth()->id(), 'title' => $request->input('title')]);

            return redirect()->route('koordinator_story_conference.jadwal.index')->with('success', 'Jadwal Story Conference berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Failed to save StoryConference jadwal', ['error' => $e->getMessage()]);

            return redirect()->route('koordinator_story_conference.jadwal.index')->with('error', 'Gagal menyimpan jadwal. Silakan coba lagi.');
        }
    }
}

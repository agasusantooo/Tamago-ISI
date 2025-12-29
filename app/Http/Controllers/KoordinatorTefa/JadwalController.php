<?php

namespace App\Http\Controllers\KoordinatorTefa;

use App\Http\Controllers\Controller;
use App\Models\JadwalAcara;
use Illuminate\Http\Request;

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
        if (! auth()->user() || ! auth()->user()->hasRole('koordinator_tefa')) {
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
            JadwalAcara::updateOrCreate(
                ['type' => $this->eventType],
                [
                    'title' => $request->input('title'),
                    'start' => $request->input('start'),
                    'end' => $request->input('end'),
                ]
            );

            \Log::info('TEFA jadwal updated', ['by' => auth()->id(), 'title' => $request->input('title')]);

            return redirect()->route('koordinator_tefa.jadwal.index')->with('success', 'Jadwal TEFA Fair berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Failed to save TEFA jadwal', ['error' => $e->getMessage()]);

            return redirect()->route('koordinator_tefa.jadwal.index')->with('error', 'Gagal menyimpan jadwal. Silakan coba lagi.');
        }
    }
}

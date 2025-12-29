<?php

namespace App\Http\Controllers\KoordinatorStoryConference;

use App\Http\Controllers\Controller;
use App\Models\StoryConference;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $registrations = StoryConference::with('mahasiswa.user', 'proposal')->get();

        return view('koordinator_story_conference.monitoring', compact('registrations'));
    }

    public function approve(Request $request, $id)
    {
        if (! auth()->user() || ! auth()->user()->hasRole('koordinator_story_conference')) {
            abort(403, 'Unauthorized');
        }

        $registration = StoryConference::findOrFail($id);

        if (in_array($registration->status, ['diterima', 'ditolak'])) {
            return redirect()->route('koordinator_story_conference.monitoring')->with('warning', 'Status pendaftaran sudah final.');
        }

        try {
            $registration->status = 'diterima';
            $registration->tanggal_review = now();
            $registration->save();

            \Log::info('Story Conference registration approved', ['id' => $registration->{$registration->getKeyName()}, 'by' => auth()->id()]);

            return redirect()->route('koordinator_story_conference.monitoring')->with('success', 'Pendaftaran Story Conference berhasil disetujui.');
        } catch (\Exception $e) {
            \Log::error('Failed to approve Story Conference registration', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('koordinator_story_conference.monitoring')->with('error', 'Terjadi kesalahan saat menyetujui pendaftaran.');
        }
    }

    public function reject(Request $request, $id)
    {
        if (! auth()->user() || ! auth()->user()->hasRole('koordinator_story_conference')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'reason' => 'nullable|string|min:5|max:1000',
        ], [
            'reason.min' => 'Alasan penolakan harus minimal 5 karakter.',
            'reason.max' => 'Alasan penolakan terlalu panjang.',
        ]);

        $registration = StoryConference::findOrFail($id);

        if (in_array($registration->status, ['diterima', 'ditolak'])) {
            return redirect()->route('koordinator_story_conference.monitoring')->with('warning', 'Status pendaftaran sudah final.');
        }

        try {
            $registration->status = 'ditolak';
            if ($request->filled('reason')) {
                $registration->catatan_panitia = $request->input('reason');
            }
            $registration->tanggal_review = now();
            $registration->save();

            \Log::info('Story Conference registration rejected', ['id' => $registration->{$registration->getKeyName()}, 'reason' => $request->input('reason') ?? null, 'by' => auth()->id()]);

            return redirect()->route('koordinator_story_conference.monitoring')->with('success', 'Pendaftaran Story Conference berhasil ditolak.');
        } catch (\Exception $e) {
            \Log::error('Failed to reject Story Conference registration', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->route('koordinator_story_conference.monitoring')->with('error', 'Terjadi kesalahan saat menolak pendaftaran.');
        }
    }
}

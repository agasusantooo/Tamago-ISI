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
        $registration = StoryConference::findOrFail($id);
        $registration->status = 'approved';
        $registration->save();
        return redirect()->route('koordinator_story_conference.monitoring')->with('success', 'Pendaftaran Story Conference disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $registration = StoryConference::findOrFail($id);
        $registration->status = 'rejected';
        $registration->save();
        return redirect()->route('koordinator_story_conference.monitoring')->with('error', 'Pendaftaran Story Conference ditolak.');
    }
}

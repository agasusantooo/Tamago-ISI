<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\TAProgressStage; // Still needed if TA stages are displayed elsewhere
use App\Models\Timeline; // Still needed if timeline due dates are set by others
use App\Models\ActivitySemester;
use Carbon\Carbon;

class SetupController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('tanggal_mulai', 'desc')->get();
        return view('kaprodi.setup', compact('semesters'));
    }

    public function storeSemester(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // If 'is_active' is checked, deactivate all other semesters
        if ($request->has('is_active')) {
            Semester::query()->update(['is_active' => false]);
        }

        Semester::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('kaprodi.setup')->with('success', 'Periode semester berhasil ditambahkan.');
    }

    public function configureActivitySemesters()
    {
        $semesters = Semester::orderBy('tanggal_mulai', 'desc')->get();
        $activitySemesters = ActivitySemester::all()->keyBy('type');
        $activityTypes = ['seminar' => 'Seminar', 'tefa' => 'TEFA Fair', 'ta' => 'Tugas Akhir'];

        return view('kaprodi.timeline', compact('semesters', 'activitySemesters', 'activityTypes'));
    }

    public function storeActivitySemesters(Request $request)
    {
        $request->validate([
            'seminar_semester_id' => 'nullable|exists:semesters,id',
            'tefa_semester_id' => 'nullable|exists:semesters,id',
            'ta_semester_id' => 'nullable|exists:semesters,id',
        ]);

        $activityTypes = [
            'seminar' => $request->seminar_semester_id,
            'tefa' => $request->tefa_semester_id,
            'ta' => $request->ta_semester_id,
        ];

        foreach ($activityTypes as $type => $semesterId) {
            if ($semesterId) {
                ActivitySemester::updateOrCreate(
                    ['type' => $type],
                    ['semester_id' => $semesterId]
                );
            } else {
                // If a type is deselected, remove its entry
                ActivitySemester::where('type', $type)->delete();
            }
        }

        return redirect()->route('kaprodi.timeline')->with('success', 'Pengaturan semester kegiatan berhasil disimpan.');
    }
}

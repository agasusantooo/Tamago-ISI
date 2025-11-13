<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class JadwalBimbinganController extends Controller
{
    public function index()
    {
        $events = Jadwal::where('user_id', Auth::id())->get();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start'
        ]);

        $jadwal = Jadwal::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start'
        ]);

        $jadwal->update($request->only('title', 'start', 'end'));

        return response()->json($jadwal);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $jadwal->delete();

        return response()->json(['status' => 'success']);
    }
}
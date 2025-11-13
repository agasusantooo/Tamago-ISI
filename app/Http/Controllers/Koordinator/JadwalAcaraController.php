<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalAcara;

class JadwalAcaraController extends Controller
{
    public function index()
    {
        return response()->json(JadwalAcara::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);

        $jadwal = JadwalAcara::create($request->all());

        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalAcara::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        return response()->json($jadwal);
    }

    public function destroy($id)
    {
        $jadwal = JadwalAcara::findOrFail($id);
        $jadwal->delete();

        return response()->json(['status' => 'success']);
    }
}
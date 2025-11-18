<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenPembimbingController extends Controller
{
    // List all dosen pembimbing
    public function index()
    {
        $dosen = Dosen::where('status', 'aktif')->get();
        return response()->json($dosen);
    }

    // Show single dosen pembimbing
    public function show($nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        return response()->json($dosen);
    }

    // Create new dosen pembimbing
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|string|unique:dosen,nidn',
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'rumpun_ilmu' => 'required|string',
            'status' => 'required|string',
        ]);
        $dosen = Dosen::create($validated);
        return response()->json($dosen, 201);
    }

    // Update dosen pembimbing
    public function update(Request $request, $nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string',
            'jabatan' => 'sometimes|required|string',
            'rumpun_ilmu' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
        ]);
        $dosen->update($validated);
        return response()->json($dosen);
    }

    // Delete dosen pembimbing
    public function destroy($nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        $dosen->delete();
        return response()->json(['message' => 'Dosen pembimbing deleted']);
    }
}

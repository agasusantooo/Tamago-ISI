<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class JadwalBimbinganController extends Controller
{
    public function index()
    {
        $nidn = Auth::user()->nidn;
        
        // Ambil semua jadwal bimbingan yang terkait dengan dosen ini
        $events = Bimbingan::where('dosen_nidn', $nidn)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($j) {
                $mahasiswaName = 'Mahasiswa';
                if ($j->nim) {
                    $m = Mahasiswa::where('nim', $j->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $j->nim;
                }

                // Map status database ke status UI: pending->pending, disetujui->approved, ditolak->rejected
                $statusDb = $j->status ?? 'pending';
                $statusUI = match($statusDb) {
                    'disetujui' => 'approved',
                    'ditolak' => 'rejected',
                    default => 'pending'
                };

                return (object)[
                    'id' => $j->id_bimbingan ?? $j->id,
                    'title' => ($j->topik ?? 'Bimbingan') . ' - ' . $mahasiswaName,
                    'topik' => $j->topik ?? 'Bimbingan',
                    'tanggal' => $j->tanggal?->format('Y-m-d'),
                    'waktu_mulai' => $j->waktu_mulai ? $j->waktu_mulai->format('H:i') : '10:00',
                    'waktu_selesai' => $j->waktu_selesai ? $j->waktu_selesai->format('H:i') : '11:00',
                    'status' => $statusUI,
                    'mahasiswa_name' => $mahasiswaName,
                    'start' => $j->tanggal?->format('Y-m-d') . 'T' . ($j->waktu_mulai ? $j->waktu_mulai->format('H:i') : '10:00'),
                    'end' => $j->tanggal?->format('Y-m-d') . 'T' . ($j->waktu_selesai ? $j->waktu_selesai->format('H:i') : '11:00'),
                ];
            })
            ->toArray();
        
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
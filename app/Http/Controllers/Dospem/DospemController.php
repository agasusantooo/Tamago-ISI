<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class DospemController extends Controller
{
    public function mahasiswaBimbingan()
    {
           // Get mahasiswa yang dibimbing oleh dosen ini
           $nidn = Auth::user()->nidn;
           $mahasiswa = Mahasiswa::where('dosen_pembimbing_id', $nidn)->get();
           return view('dospem.mahasiswa-bimbingan', compact('mahasiswa'));
    }

    public function reviewTugas()
    {
            // Get bimbingan yang perlu direview
            $nidn = Auth::user()->nidn;
            $bimbingan = Bimbingan::where('dosen_nidn', $nidn)
                ->where('status', 'submitted')
                ->with('mahasiswa')
                ->get();
            return view('dospem.review-tugas', compact('bimbingan'));
    }

    public function riwayatBimbingan()
    {
            // Get riwayat bimbingan
            $nidn = Auth::user()->nidn;
            $bimbingan = Bimbingan::where('dosen_nidn', $nidn)
                ->with('mahasiswa')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('dospem.riwayat-bimbingan', compact('bimbingan'));
    }

    public function jadwalBimbingan()
    {
        return view('dospem.jadwal-bimbingan');
    }
}

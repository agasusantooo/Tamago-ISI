<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\ProjekAkhir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DospemController extends Controller
{
    public function mahasiswaBimbingan()
    {
        // Get mahasiswa yang dibimbing oleh dosen ini
        $nidn = Auth::user()->nidn;
        $mahasiswaBimbingan = Mahasiswa::where('dosen_pembimbing_id', $nidn)
            ->with('user', 'projekAkhir')
            ->get()
            ->map(function ($m) {
                return (object)[
                    'id' => $m->nim,
                    'nim' => $m->nim,
                    'name' => $m->nama ?? optional($m->user)->name,
                    'email' => optional($m->user)->email ?? $m->email,
                    'judul_ta' => optional($m->projekAkhir)->judul_proyek ?? 'Belum ada judul',
                    'progress' => optional($m->projekAkhir)->progress_persentase ?? 0,
                    'bimbingan_terakhir' => optional($m->projekAkhir)->updated_at?->format('Y-m-d') ?? '-'
                ];
            });

        $mahasiswaAktifCount = $mahasiswaBimbingan->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();

        return view('dospem.mahasiswa-bimbingan', compact('mahasiswaBimbingan', 'mahasiswaAktifCount', 'tugasReview'));
    }

    public function reviewTugas()
    {
        // Get bimbingan yang perlu direview
        $nidn = Auth::user()->nidn;

        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();

        $bimbingan = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($b) {
                $mahasiswaName = $b->nim ?? 'N/A';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return (object)[
                    'id' => $b->id_bimbingan ?? $b->id,
                    'topik' => $b->topik ?? $b->catatan_bimbingan ?? 'Bimbingan',
                    'mahasiswa_nim' => $b->nim,
                    'mahasiswa_name' => $mahasiswaName,
                    'created_at' => $b->created_at,
                    'status' => $b->status ?? 'pending'
                ];
            });

        return view('dospem.review-tugas', compact('bimbingan', 'mahasiswaAktifCount', 'tugasReview'));
    }

    public function riwayatBimbingan()
    {
        // Get riwayat bimbingan
        $nidn = Auth::user()->nidn;

        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();

        $bimbingan = Bimbingan::where('dosen_nidn', $nidn)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($b) {
                $mahasiswaName = $b->nim ?? 'N/A';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return (object)[
                    'id' => $b->id_bimbingan ?? $b->id,
                    'topik' => $b->topik ?? $b->catatan_bimbingan ?? 'Bimbingan',
                    'mahasiswa_name' => $mahasiswaName,
                    'created_at' => $b->created_at,
                    'tanggal' => $b->tanggal?->format('Y-m-d'),
                    'status' => $b->status ?? 'completed'
                ];
            });

        return view('dospem.riwayat-bimbingan', compact('bimbingan', 'mahasiswaAktifCount', 'tugasReview'));
    }

    public function jadwalBimbingan()
    {
        $nidn = Auth::user()->nidn;
        
        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();
        
        $jadwal = Bimbingan::where('dosen_nidn', $nidn)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($b) {
                $mahasiswaName = $b->nim ?? 'N/A';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return (object)[
                    'id' => $b->id_bimbingan ?? $b->id,
                    'topik' => $b->topik ?? $b->catatan_bimbingan ?? 'Bimbingan',
                    'mahasiswa_name' => $mahasiswaName,
                    'mahasiswa_nim' => $b->nim,
                    'tanggal' => $b->tanggal?->format('Y-m-d'),
                    'waktu' => $b->waktu_mulai ? $b->waktu_mulai->format('H:i') : '10:00',
                    'status' => $b->status ?? 'pending'
                ];
            });
        
        return view('dospem.jadwal-bimbingan', compact('jadwal', 'mahasiswaAktifCount', 'tugasReview'));
    }

    /**
     * Return review tugas data as JSON for real-time polling
     */
    public function reviewTugasData()
    {
        $nidn = Auth::user()->nidn;

        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();

        $bimbingan = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($b) {
                $mahasiswaName = $b->nim ?? 'N/A';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return [
                    'id' => $b->id_bimbingan ?? $b->id,
                    'topik' => $b->topik ?? $b->catatan_bimbingan ?? 'Bimbingan',
                    'mahasiswa_nim' => $b->nim,
                    'mahasiswa_name' => $mahasiswaName,
                    'created_at' => $b->created_at?->format('Y-m-d H:i') ?? '-',
                    'status' => $b->status ?? 'pending'
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'mahasiswaAktifCount' => $mahasiswaAktifCount,
                'tugasReview' => $tugasReview,
                'bimbingan' => $bimbingan,
            ]
        ]);
    }

    /**
     * Return jadwal bimbingan data as JSON for real-time polling
     */
    public function jadwalBimbinganData()
    {
        $nidn = Auth::user()->nidn;
        
        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();
        
        $jadwal = Bimbingan::where('dosen_nidn', $nidn)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($b) {
                $mahasiswaName = $b->nim ?? 'N/A';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return [
                    'id' => $b->id_bimbingan ?? $b->id,
                    'topik' => $b->topik ?? $b->catatan_bimbingan ?? 'Bimbingan',
                    'mahasiswa_name' => $mahasiswaName,
                    'mahasiswa_nim' => $b->nim,
                    'tanggal' => $b->tanggal?->format('Y-m-d'),
                    'waktu' => $b->waktu_mulai ? $b->waktu_mulai->format('H:i') : '10:00',
                    'status' => $b->status ?? 'pending'
                ];
            });
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'mahasiswaAktifCount' => $mahasiswaAktifCount,
                'tugasReview' => $tugasReview,
                'jadwal' => $jadwal,
            ]
        ]);
    }

    /**
     * Return riwayat bimbingan data as JSON for real-time polling
     */
    public function riwayatBimbinganData()
    {
        $nidn = Auth::user()->nidn;

        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();

        $bimbingan = Bimbingan::where('dosen_nidn', $nidn)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($b) {
                $mahasiswaName = $b->nim ?? 'N/A';
                if ($b->nim) {
                    $m = Mahasiswa::where('nim', $b->nim)->first();
                    $mahasiswaName = $m ? ($m->nama ?? optional($m->user)->name) : $b->nim;
                }

                return [
                    'id' => $b->id_bimbingan ?? $b->id,
                    'topik' => $b->topik ?? $b->catatan_bimbingan ?? 'Bimbingan',
                    'mahasiswa_name' => $mahasiswaName,
                    'created_at' => $b->created_at?->format('Y-m-d H:i') ?? '-',
                    'tanggal' => $b->tanggal?->format('Y-m-d'),
                    'status' => $b->status ?? 'completed'
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'mahasiswaAktifCount' => $mahasiswaAktifCount,
                'tugasReview' => $tugasReview,
                'bimbingan' => $bimbingan,
            ]
        ]);
    }

    public function getMahasiswaBimbinganDataJson()
    {
        $nidn = Auth::user()->nidn;

        // Stats untuk header
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();

        // Get mahasiswa bimbingan dengan data lengkap
        $mahasiswa = Mahasiswa::where('dosen_pembimbing_id', $nidn)
            ->with(['user', 'projekAkhir'])
            ->get()
            ->map(function ($m) {
                $lastBimbingan = Bimbingan::where('nim', $m->nim)
                    ->orderBy('created_at', 'desc')
                    ->first();

                return [
                    'nim' => $m->nim,
                    'name' => $m->nama ?? optional($m->user)->name ?? 'Mahasiswa',
                    'email' => optional($m->user)->email ?? '-',
                    'judul_ta' => optional($m->projekAkhir)->judul ?? 'Belum ada judul',
                    'progress' => random_int(20, 100), // TODO: Hitung dari submission data
                    'bimbingan_terakhir' => $lastBimbingan ? $lastBimbingan->created_at?->format('Y-m-d') ?? '-' : '-',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'mahasiswaAktifCount' => $mahasiswaAktifCount,
                'tugasReview' => $tugasReview,
                'mahasiswaBimbingan' => $mahasiswa,
            ]
        ]);
    }
}

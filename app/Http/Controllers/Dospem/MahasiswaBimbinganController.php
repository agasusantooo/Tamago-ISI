<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Bimbingan;
use App\Models\Proposal;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MahasiswaBimbinganController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa bimbingan.
     */
    public function index()
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

    /**
     * Menampilkan halaman detail mahasiswa bimbingan.
     */
    public function show(Request $request, $id)
    {
        // If POST request, redirect to GET to avoid method not allowed
        if ($request->isMethod('post')) {
            return redirect()->route('dospem.mahasiswa-bimbingan.show', ['id' => $id]);
        }

        // Ambil mahasiswa dari database berdasarkan NIM atau user_id
        $mahasiswa = Mahasiswa::where('nim', $id)->orWhere('user_id', $id)->firstOrFail();
        $nidn = Auth::user()->nidn;

        // Ambil proposals dari database
        $proposals = Proposal::where('mahasiswa_nim', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'judul' => $p->judul,
                    'deskripsi' => $p->deskripsi,
                    'status' => $p->status,
                    'tanggal_pengajuan' => $p->tanggal_pengajuan?->format('Y-m-d'),
                    'file_proposal' => $p->file_proposal,
                ];
            })
            ->toArray();

        // Ambil jadwal bimbingan yang diajukan mahasiswa
        $jadwal_bimbingan = Bimbingan::where(function($q) use ($mahasiswa) {
                $q->where('nim', $mahasiswa->nim)
                  ->orWhere('mahasiswa_id', $mahasiswa->id);
            })
            ->where('dosen_nidn', $nidn)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($j) {
                return [
                    'id' => $j->id_bimbingan ?? $j->id,
                    'tanggal' => $j->tanggal?->format('Y-m-d'),
                    'waktu' => $j->waktu_mulai ?? '10:00',
                    'topik' => $j->topik ?? 'Bimbingan',
                    'deskripsi' => $j->catatan_mahasiswa ?? '',
                    'created_at' => $j->created_at?->format('Y-m-d H:i:s'),
                    'status' => $j->status ?? 'pending',
                ];
            })
            ->toArray();

        // Ambil riwayat bimbingan yang sudah selesai
                $riwayat_bimbingan = Bimbingan::where(function($q) use ($mahasiswa) {
                                $q->where('nim', $mahasiswa->nim)
                                    ->orWhere('mahasiswa_id', $mahasiswa->id);
                        })
                        ->where('dosen_nidn', $nidn)
                        ->whereIn('status', ['completed', 'disetujui', 'selesai'])
                        ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($r) {
                return [
                    'tanggal' => $r->tanggal?->format('Y-m-d'),
                    'waktu' => $r->waktu_mulai ? $r->waktu_mulai->format('H:i') : '',
                    'topik' => $r->topik ?? 'Bimbingan',
                    'catatan' => $r->catatan_dosen ?? $r->catatan_bimbingan,
                    'catatan_mahasiswa' => $r->catatan_mahasiswa,
                    'file' => $r->file_pendukung,
                    'status' => $r->status ?? 'Selesai',
                ];
            })
            ->toArray();

            // Attach arrays onto the mahasiswa object so view can access them as properties
            // (the view expects $mahasiswa->riwayat_bimbingan etc.)
            $mahasiswa->riwayat_bimbingan = $riwayat_bimbingan;
            $mahasiswa->jadwal_bimbingan = $jadwal_bimbingan;
            $mahasiswa->proposals = $proposals;
            // Get produksi list
            $produksiController = new MahasiswaProduksiController();
            $produksi = $produksiController->getProduksiList($mahasiswa->nim);

            // Provide header variables expected by the partials
        $mahasiswaAktifCount = Mahasiswa::where('dosen_pembimbing_id', $nidn)->count();
        $tugasReview = Bimbingan::where('dosen_nidn', $nidn)
            ->whereIn('status', ['pending', 'diajukan', 'review'])
            ->count();
        $jumlahMahasiswaAktif = $mahasiswaAktifCount;
        $jumlahTugasReview = $tugasReview;

        return view('dospem.detail-mahasiswa', compact(
            'mahasiswa',
            'mahasiswaAktifCount',
            'tugasReview',
            'jumlahMahasiswaAktif',
            'jumlahTugasReview',
            'proposals',
            'jadwal_bimbingan',
            'riwayat_bimbingan',
            'produksi'
        ));
    }

    /**
     * Menerima dan menyimpan feedback singkat dari dospem (saat tombol Submit diklik).
     */
    public function submitFeedback(\Illuminate\Http\Request $request, $id)
    {
        // Validasi input
        $data = $request->validate([
            'catatan' => 'nullable|string|max:2000',
            'catatan_ringkas' => 'nullable|string|max:1000',
            'progress_level' => 'nullable|in:0,25,50,75,100',
            'target_date' => 'nullable|date',
        ]);

        // Simpan feedback ke proposal (kolom feedback)
        $proposal = Proposal::where('mahasiswa_nim', $id)->latest()->first();
        if ($proposal) {
            // Otorisasi: hanya dosen pembimbing yang boleh memberi feedback
            if ($proposal->dosen_id != Auth::id()) {
                abort(403, 'Anda tidak berhak memberi feedback pada proposal ini.');
            }
            $proposal->feedback = $data['catatan'] ?? $data['catatan_ringkas'] ?? '';
            $proposal->save();
        }
        return redirect()->back()->with('success', 'Feedback proposal berhasil disimpan.');
    }

    /**
     * Approve a proposal
     */
    public function approveProposal(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        // Otorisasi: hanya dosen pembimbing yang boleh ACC
        if ($proposal->dosen_id != Auth::id()) {
            abort(403, 'Anda tidak berhak mengubah status proposal ini.');
        }
        $updateData = [
            'status' => 'disetujui',
            'tanggal_review' => now(),
        ];

        if ($request->has('feedback')) {
            $updateData['feedback'] = $request->input('feedback');
        }

        $proposal->update($updateData);

        // Update mahasiswa status to reflect proposal approval
        $mahasiswa = Mahasiswa::where('nim', $proposal->mahasiswa_nim)->first();
        if ($mahasiswa) {
            $mahasiswa->update(['status' => 'proposal_disetujui']);
        }

        return redirect()->back()->with('success', 'Proposal berhasil disetujui!');
    }

    /**
     * Reject a proposal
     */
    public function rejectProposal(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        // Otorisasi: hanya dosen pembimbing yang boleh Tolak
        if ($proposal->dosen_id != Auth::id()) {
            abort(403, 'Anda tidak berhak mengubah status proposal ini.');
        }
        $updateData = [
            'status' => 'ditolak',
            'tanggal_review' => now(),
        ];

        if ($request->has('feedback')) {
            $updateData['feedback'] = $request->input('feedback');
        }

        $proposal->update($updateData);
        // Update mahasiswa status to reflect proposal rejection
        $mahasiswa = Mahasiswa::where('nim', $proposal->mahasiswa_nim)->first();
        if ($mahasiswa) {
            $mahasiswa->update(['status' => 'proposal_ditolak']);
        }

        return redirect()->back()->with('success', 'Proposal berhasil ditolak.');
    }

    /**
     * Approve a bimbingan session
     */
    public function approveBimbingan(Request $request, $id)
    {
        try {
            // Find bimbingan by its primary id (id_bimbingan atau id)
            $bimbingan = Bimbingan::where('id_bimbingan', $id)
                ->orWhere('id', $id)
                ->firstOrFail();

            // Ensure current dosen is owner of this bimbingan
            $nidn = Auth::user()->nidn ?? null;
            if ($bimbingan->dosen_nidn && $nidn && $bimbingan->dosen_nidn !== $nidn) {
                return $this->handleResponse($request, 'error', 'Anda tidak berhak menyetujui jadwal ini.', 403);
            }

            // Update bimbingan status to 'disetujui'
            $bimbingan->status = 'disetujui';
            $bimbingan->catatan_dosen = $request->input('catatan_ringkas') ?? $request->input('alasan') ?? $bimbingan->catatan_dosen;
            $bimbingan->updated_at = now();
            $bimbingan->save();

            // Update mahasiswa status so mahasiswa role sees the change
            if (!empty($bimbingan->nim)) {
                $mahasiswa = Mahasiswa::where('nim', $bimbingan->nim)->first();
                if ($mahasiswa) {
                    $mahasiswa->status = 'bimbingan_disetujui';
                    $mahasiswa->save();
                }
            }

            return $this->handleResponse($request, 'success', '✅ Jadwal bimbingan berhasil diterima!', 200);
        } catch (\Exception $e) {
            \Log::error('approveBimbingan error: ' . $e->getMessage());
            return $this->handleResponse($request, 'error', '❌ Gagal menerima jadwal: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reject a bimbingan session
     */
    public function rejectBimbingan(Request $request, $id)
    {
        try {
            // Find bimbingan by its primary id (id_bimbingan atau id)
            $bimbingan = Bimbingan::where('id_bimbingan', $id)
                ->orWhere('id', $id)
                ->firstOrFail();

            // Ensure current dosen is owner
            $nidn = Auth::user()->nidn ?? null;
            if ($bimbingan->dosen_nidn && $nidn && $bimbingan->dosen_nidn !== $nidn) {
                return $this->handleResponse($request, 'error', 'Anda tidak berhak menolak jadwal ini.', 403);
            }

            // Update bimbingan status to 'ditolak'
            $bimbingan->status = 'ditolak';
            $bimbingan->catatan_dosen = $request->input('alasan') ?? ($request->input('catatan_ringkas') ?? $bimbingan->catatan_dosen);
            $bimbingan->updated_at = now();
            $bimbingan->save();

            // Update mahasiswa status
            if (!empty($bimbingan->nim)) {
                $mahasiswa = Mahasiswa::where('nim', $bimbingan->nim)->first();
                if ($mahasiswa) {
                    $mahasiswa->status = 'bimbingan_ditolak';
                    $mahasiswa->save();
                }
            }

            return $this->handleResponse($request, 'success', '✅ Jadwal bimbingan berhasil ditolak.', 200);
        } catch (\Exception $e) {
            \Log::error('rejectBimbingan error: ' . $e->getMessage());
            return $this->handleResponse($request, 'error', '❌ Gagal menolak jadwal: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update status proposal dari dosen pembimbing
     */
    public function updateProposalStatus(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'status' => 'required|in:review,revisi,disetujui,ditolak',
                'feedback' => 'required|string|min:5',
            ]);

            // Cari proposal berdasarkan ID
            $proposal = Proposal::findOrFail($id);

            // Update status dan feedback
            $proposal->update([
                'status' => $validated['status'],
                'feedback' => $validated['feedback'],
                'tanggal_review' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status proposal berhasil diperbarui!',
                'status' => $validated['status'],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Proposal update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to handle responses (JSON or redirect)
     */
    private function handleResponse($request, $status, $message, $httpCode = 200)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'success' => $status === 'success',
                'message' => $message,
                'code' => $httpCode
            ], $httpCode);
        }

        return redirect()->back()->with(
            $status === 'success' ? 'success' : 'error',
            $message
        );
    }
}

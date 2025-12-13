<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TefaFair;
use App\Models\ProjekAkhir;

class TefaFairController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        $statusBadges = [
            'menunggu_review' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu Review'],
            'disetujui' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
            'ditolak' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
        ];

        // Ambil TEFA Fair history dari database
        // Query by nim first, then fallback to all tefas if needed
        $history = collect();
        
        if ($mahasiswa && $mahasiswa->nim) {
            $tefaFairs = TefaFair::where('mahasiswa_nim', $mahasiswa->nim)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $history = $tefaFairs->map(function($tefa) use ($statusBadges) {
                $tefa->statusBadge = $statusBadges[$tefa->status] ?? $statusBadges['menunggu_review'];
                // Add judul_proyek placeholder (since tefa_fair doesn't store it, use semester as display)
                $tefa->judul_proyek = $tefa->semester ?? 'Tefa Fair';
                return $tefa;
            });
        }
        

        $jadwalTefaFair = [
            [
                'jenis' => 'Tefa Fair Exhibition',
                'tanggal' => '28 - 30 Desember 2024',
                'waktu' => '10:00 - 21:00 WIB',
                'tempat' => 'Gedung Pameran Utama ISI',
                'deskripsi' => 'Pameran karya akhir mahasiswa dari berbagai program studi. Terbuka untuk umum.',
                'persyaratan' => [
                    'Karya akhir telah disetujui untuk dipamerkan.',
                    'Menyiapkan materi pameran (display, poster, produk, dll).',
                    'Mengisi form pendaftaran dan daftar kebutuhan pameran.'
                ],
                'bg_color' => 'bg-green-50',
                'border_color' => 'border-green-500'
            ]
        ];

        return view('mahasiswa.tefa-fair.index', compact('jadwalTefaFair', 'history'));
    }

    public function create()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.proposal.index')
                ->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        // Ambil projek akhir mahasiswa dari database
        $projekAkhir = ProjekAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->orWhere('nim', $mahasiswa->nim)
            ->first();

        if (!$projekAkhir) {
            return redirect()->route('mahasiswa.tefa-fair.index')
                ->with('error', 'Anda belum memiliki proyek akhir. Silakan selesaikan tahap produksi terlebih dahulu.');
        }

        // Cek apakah sudah terdaftar
        $tefaFair = TefaFair::where('mahasiswa_nim', $mahasiswa->nim)
            ->where('proposal_id', $projekAkhir->proposal_id)
            ->first();

        return view('mahasiswa.tefa-fair.create', compact('projekAkhir', 'tefaFair'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // The TEFA Fair form uses fields: semester, daftar_kebutuhan, file_presentasi
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'semester' => 'required|string|max:255',
            'daftar_kebutuhan' => 'required|string|min:5',
            'file_presentasi' => 'nullable|file|mimes:pdf,ppt,pptx|max:20480', // 20 MB
        ], [
            'semester.required' => 'Semester wajib diisi',
            'daftar_kebutuhan.required' => 'Daftar kebutuhan pameran wajib diisi',
            'file_presentasi.mimes' => 'File presentasi harus berformat PDF atau PPT',
            'file_presentasi.max' => 'File presentasi maksimal 20 MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $projekAkhir = ProjekAkhir::where('mahasiswa_id', $mahasiswa->id)
                ->orWhere('nim', $mahasiswa->nim)
                ->first();

            if (!$projekAkhir) {
                return back()->with('error', 'Proyek akhir tidak ditemukan.');
            }

            // Handle file presentasi (slides/poster)
            $filePath = null;
            if ($request->hasFile('file_presentasi')) {
                $file = $request->file('file_presentasi');
                $fileName = 'tefa_presentasi_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs(
                    'tefa-fair/' . $mahasiswa->nim,
                    $fileName,
                    'public'
                );
            }

            // Get existing record to preserve file path if no new upload
            $existing = TefaFair::where('mahasiswa_nim', $mahasiswa->nim)->first();

            // Save or update TEFA Fair (match by mahasiswa_nim only)
            TefaFair::updateOrCreate(
                ['mahasiswa_nim' => $mahasiswa->nim],
                [
                    'mahasiswa_nim' => $mahasiswa->nim,
                    'semester' => $request->input('semester'),
                    'daftar_kebutuhan' => $request->input('daftar_kebutuhan'),
                    'file_presentasi' => $filePath ?: ($existing->file_presentasi ?? null),
                    'status' => 'menunggu_review',
                ]
            );

            return redirect()->route('mahasiswa.tefa-fair.index')
                ->with('success', 'Pendaftaran TEFA Fair berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
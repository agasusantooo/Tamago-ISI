<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class BimbinganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            Log::error("Mahasiswa profile not found for user: {$user->id} ({$user->email}) - {$user->name}");
            return redirect()->route('mahasiswa.proposal')->with('error', 'Profil mahasiswa tidak ditemukan. Hubungi administrator untuk verifikasi data.');
        }

                // Ambil riwayat bimbingan mahasiswa dari database (hanya milik user yang login)
                // Pertahankan kompatibilitas: beberapa record lama mungkin hanya menyimpan `nim`.
                $bimbinganList = Bimbingan::where(function ($q) use ($mahasiswa) {
                                $q->where('mahasiswa_id', $mahasiswa->user_id)
                                    ->orWhere('nim', $mahasiswa->nim);
                        })
                        ->orderBy('created_at', 'desc')
                        ->get()
            ->map(function ($item) {
                // Tambahkan warna dan teks status untuk tampilan
                $statusMap = [
                    'pending' => ['text' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-800'],
                    'disetujui' => ['text' => 'Disetujui', 'color' => 'bg-green-100 text-green-800'],
                    'ditolak' => ['text' => 'Ditolak', 'color' => 'bg-red-100 text-red-800'],
                ];

                $status = $statusMap[$item->status] ?? ['text' => 'Tidak Diketahui', 'color' => 'bg-gray-100 text-gray-800'];

                $item->statusBadge = ['text' => $status['text']];
                $item->statusColor = $status['color'];
                $item->tanggal = $item->tanggal ?? now();

                return $item;
            });

        // Jadwal bimbingan yang sudah dijadwalkan
        $jadwalTerjadwal = $bimbinganList->where('status', 'disetujui');

        return view('mahasiswa.bimbingan', compact('bimbinganList', 'jadwalTerjadwal'));
    }

    public function store(Request $request)
    {
        // Log the incoming payload to help debug missing/renamed fields
        Log::info('Bimbingan submit payload (before merge)', $request->all());

        // Accept old field name 'catatan' for backward compatibility.
        $catatan = $request->input('catatan_mahasiswa') ?? $request->input('catatan');
        if ($catatan !== null) {
            $request->merge(['catatan_mahasiswa' => $catatan]);
        }

        Log::info('Bimbingan submit payload (after merge)', $request->only(['topik', 'catatan_mahasiswa']));

        // Validasi input
        $request->validate([
            'topik' => 'required|string|max:255',
            'catatan_mahasiswa' => 'required|string|min:5',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'tanggal' => 'nullable|date|after_or_equal:today',
            'waktu_mulai' => 'nullable|date_format:H:i',
        ]);

    $user = Auth::user();

    // Cek relasi mahasiswa
    $mahasiswa = $user->mahasiswa;
    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    // Proyek akhir opsional - bimbingan bisa dilakukan kapan saja
    $projekAkhir = $mahasiswa->projek_akhir ?? null;

    // Handle file pendukung
    $filePath = null;
    if ($request->hasFile('file_pendukung')) {
        $filePath = $request->file('file_pendukung')->store('bimbingan_files', 'public');
    }

        // Determine tanggal (use submitted tanggal if provided, otherwise default to now)
        $submittedTanggal = $request->input('tanggal');
        $tanggalToStore = $submittedTanggal ? \Carbon\Carbon::parse($submittedTanggal)->startOfDay() : now();

        // Waktu mulai yang dipilih (optional)
        $waktuMulai = $request->input('waktu_mulai');

        // Simple conflict check: jika ada jadwal disetujui pada tanggal dan jam yang sama, tolak
        if ($waktuMulai) {
            $conflict = Bimbingan::whereDate('tanggal', $tanggalToStore)
                ->where('waktu_mulai', $waktuMulai)
                ->where('status', 'disetujui')
                ->exists();

            if ($conflict) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Slot pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.');
            }
        }

        // Insert data bimbingan — simpan juga `mahasiswa_id` (user id) untuk relasi yang konsisten
        Bimbingan::create([
            'id_proyek_akhir' => $projekAkhir?->id_proyek_akhir,
            'mahasiswa_id' => $user->id,
            'nim' => $mahasiswa->nim,
            'topik' => $request->topik,
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
            'file_pendukung' => $filePath,
            'status' => 'pending',
            'tanggal' => $tanggalToStore,
            'waktu_mulai' => $waktuMulai,
        ]);

    return redirect()
        ->route('mahasiswa.bimbingan.index')
        ->with('success', 'Pengajuan bimbingan berhasil dikirim!');
}


    public function download($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $bimbingan = Bimbingan::findOrFail($id);

        // Pastikan file hanya dapat diunduh oleh pemilik bimbingan
        if ($mahasiswa && $bimbingan->mahasiswa_id !== $mahasiswa->user_id && $bimbingan->nim !== $mahasiswa->nim) {
            abort(403, 'Akses ditolak.');
        }

        if (!$bimbingan->file_pendukung) {
            return back()->with('error', 'Tidak ada file pendukung untuk diunduh.');
        }

        return Storage::disk('public')->download($bimbingan->file_pendukung);
    }

    public function show($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $bimbingan = Bimbingan::findOrFail($id);

        // Hanya perbolehkan mahasiswa pemilik melihat detail
        if ($mahasiswa && $bimbingan->mahasiswa_id !== $mahasiswa->user_id && $bimbingan->nim !== $mahasiswa->nim) {
            abort(403, 'Akses ditolak.');
        }

        return view('mahasiswa.bimbingan-detail', compact('bimbingan'));
    }

    /**
     * Get bimbingan status updates via AJAX (for real-time refresh)
     */
    public function checkUpdates(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return response()->json(['error' => 'Mahasiswa not found'], 404);
        }

        // Get all bimbingan for this mahasiswa with their current status
        $bimbinganList = Bimbingan::where(function($q) use ($mahasiswa) {
            $q->where('nim', $mahasiswa->nim)
              ->orWhere('mahasiswa_id', $mahasiswa->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->get(['id_bimbingan', 'status', 'catatan_dosen', 'catatan_mahasiswa', 'topik', 'tanggal', 'updated_at'])
            ->map(function ($item) {
                return [
                    'id' => $item->id_bimbingan,
                    'status' => $item->status,
                    'topik' => $item->topik,
                    'tanggal' => $item->tanggal?->format('Y-m-d'),
                    'catatan_dosen' => $item->catatan_dosen,
                    'updated_at' => $item->updated_at?->timestamp
                ];
            });

        return response()->json([
            'success' => true,
            'bimbingan' => $bimbinganList
        ]);
    }
}

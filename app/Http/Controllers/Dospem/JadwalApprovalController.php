<?php

namespace App\Http\Controllers\Dospem;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalApprovalController extends Controller
{
    /**
     * Get jadwal data for API
     */
    public function getJadwal($id)
    {
        try {
            $jadwal = Jadwal::with(['mahasiswa', 'dosen'])->find($id);
            
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $jadwal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve jadwal bimbingan
     */
    public function approve($id)
    {
        try {
            $jadwal = Jadwal::find($id);
            
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            if ($jadwal->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah diproses sebelumnya'
                ], 400);
            }

            // Update jadwal
            $jadwal->update([
                'status' => 'disetujui',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal bimbingan berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject jadwal bimbingan
     */
    public function reject($id, Request $request)
    {
        try {
            $jadwal = Jadwal::find($id);
            
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            if ($jadwal->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah diproses sebelumnya'
                ], 400);
            }

            // Update jadwal
            $jadwal->update([
                'status' => 'ditolak',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->input('reason'),
            ]);

            // Update mahasiswa status to reflect jadwal rejection
            if ($jadwal->mahasiswa_id) {
                $mahasiswa = Mahasiswa::where('user_id', $jadwal->mahasiswa_id)->first();
                if ($mahasiswa) {
                    $mahasiswa->update(['status' => 'jadwal_ditolak']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Jadwal bimbingan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all jadwal for dosen
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get jadwal for authenticated dosen (by nidn from user username)
            $jadwals = Jadwal::where('nidn', $user->username)
                ->with(['mahasiswa', 'dosen'])
                ->orderBy('tanggal', 'desc')
                ->get();

            return view('dospem.jadwal-bimbingan-new', [
                'jadwals' => $jadwals,
            ]);
        } catch (\Exception $e) {
            return view('dospem.jadwal-bimbingan-new', [
                'jadwals' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
}

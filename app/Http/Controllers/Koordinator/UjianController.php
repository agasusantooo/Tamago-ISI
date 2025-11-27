<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UjianTA;
use App\Models\ProjekAkhir;

class UjianController extends Controller
{
    /**
     * Update status of an UjianTA record. Expects JSON or form body:
     * - ujian_id or nim (prefer ujian_id)
     * - status (string)
     */
    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        if (! $user || ! $user->hasRole('koordinator_ta')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->only(['ujian_id', 'nim', 'status']);
        $status = $data['status'] ?? null;
        if (! $status) {
            return response()->json(['success' => false, 'message' => 'Missing status'], 422);
        }

        try {
            $ujian = null;
            if (! empty($data['ujian_id'])) {
                $ujian = UjianTA::where('id_ujian', $data['ujian_id'])->first();
            } elseif (! empty($data['nim'])) {
                $projek = ProjekAkhir::where('nim', $data['nim'])->latest()->first();
                if ($projek) {
                    $ujian = UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first();
                }
            }

            if (! $ujian) {
                return response()->json(['success' => false, 'message' => 'Ujian record not found'], 404);
            }

            // Only allow certain statuses to be set here
            $allowed = ['belum_upload','menunggu_review','disetujui','revisi','ditolak','pengajuan_ujian','jadwal_ditetapkan','ujian_berlangsung','selesai_ujian'];
            if (! in_array($status, $allowed)) {
                return response()->json(['success' => false, 'message' => 'Invalid status'], 422);
            }

            $ujian->status_pendaftaran = $status;
            $ujian->save();

            Log::info('Koordinator updated ujian status', ['ujian_id' => $ujian->id_ujian ?? $ujian->getKey(), 'status' => $status, 'user' => optional($user)->id]);

            return response()->json(['success' => true, 'message' => 'Status updated', 'status' => $status]);
        } catch (\Exception $e) {
            Log::error('Failed updateStatus', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }
}

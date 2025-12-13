<?php

namespace App\Service;

use App\Models\TAProgressStage;
use App\Models\Proposal;
use App\Models\Bimbingan;
use App\Models\StoryConference;
use App\Models\Produksi;
use App\Models\UjianTA;
use App\Models\ProjekAkhir;
use App\Models\Mahasiswa;

class ProgressService
{
    /**
     * Compute dashboard progress data for a mahasiswa (by user id).
     * Returns array with keys: percentage (0-100), details (per-stage info), completed_count, total_stages
     */
    public function getDashboardData($userId)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return [
                'percentage' => 0,
                'details' => [],
                'completed_count' => 0,
                'total_stages' => 0,
            ];
        }

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return [
                'percentage' => 0,
                'details' => [],
                'completed_count' => 0,
                'total_stages' => 0,
            ];
        }

        $nim = $mahasiswa->nim;

        $stages = TAProgressStage::getActiveStages();
        if ($stages->isEmpty()) {
            return [
                'percentage' => 0,
                'details' => [],
                'completed_count' => 0,
                'total_stages' => 0,
            ];
        }

        $totalWeight = $stages->sum('weight');
        $accumulated = 0; // sum of weight * fraction
        $details = [];
        $completedCount = 0;

        foreach ($stages as $stage) {
            $code = $stage->stage_code;
            $weight = (float) $stage->weight;
            $fraction = 0.0; // 0..1

            switch ($code) {
                case 'proposal_submission':
                    $submitted = Proposal::where('mahasiswa_nim', $nim)
                        ->whereNotNull('tanggal_pengajuan')
                        ->exists();
                    $fraction = $submitted ? 1.0 : 0.0;
                    break;

                case 'proposal_approved':
                    $approved = Proposal::where('mahasiswa_nim', $nim)
                        ->where('status', 'disetujui')
                        ->exists();
                    $fraction = $approved ? 1.0 : 0.0;
                    break;

                case 'bimbingan_progress':
                    // Count both submitted (pending) and approved sessions so student's submissions reflect progress
                    $doneCount = Bimbingan::where(function($q) use ($userId, $nim) {
                            $q->where('mahasiswa_id', $userId)
                              ->orWhere('nim', $nim);
                        })
                        ->whereIn('status', ['pending', 'disetujui'])
                        ->count();

                    $required = 8; // minimum sessions
                    $fraction = min($doneCount / $required, 1.0);
                    break;

                case 'story_conference':
                    $accepted = StoryConference::where('mahasiswa_id', $userId)
                        ->accepted()
                        ->exists();
                    $fraction = $accepted ? 1.0 : 0.0;
                    break;

                case 'production_upload':
                    $produksiDone = Produksi::where('mahasiswa_id', $userId)
                        ->where('status_produksi', 'disetujui')
                        ->exists();
                    $fraction = $produksiDone ? 1.0 : 0.0;
                    break;

                case 'exam_registration':
                    // UjianTA links to ProjekAkhir via id_proyek_akhir — projek_akhir has 'nim'
                    $projekIds = ProjekAkhir::where('nim', $nim)->pluck('id_proyek_akhir');
                    if ($projekIds->isEmpty()) {
                        $fraction = 0.0;
                    } else {
                        $registered = UjianTA::whereIn('id_proyek_akhir', $projekIds)
                            ->whereNotNull('status_pendaftaran')
                            ->exists();
                        $fraction = $registered ? 1.0 : 0.0;
                    }
                    break;

                case 'exam_completed':
                    $projekIds = ProjekAkhir::where('nim', $nim)->pluck('id_proyek_akhir');
                    if ($projekIds->isEmpty()) {
                        $fraction = 0.0;
                    } else {
                        $doneExam = UjianTA::whereIn('id_proyek_akhir', $projekIds)
                            ->where('status_ujian', 'selesai_ujian')
                            ->exists();
                        $fraction = $doneExam ? 1.0 : 0.0;
                    }
                    break;

                case 'final_submission':
                    $finalDoc = ProjekAkhir::where('nim', $nim)
                        ->whereNotNull('file_naskah_publikasi')
                        ->exists();
                    if (!$finalDoc) {
                        $finalDoc = Produksi::where('mahasiswa_id', $userId)
                            ->where('status_produksi', 'disetujui')
                            ->exists();
                    }
                    $fraction = $finalDoc ? 1.0 : 0.0;
                    break;

                default:
                    // Unknown stage - leave fraction 0
                    $fraction = 0.0;
            }

            $contribution = $weight * $fraction;
            $accumulated += $contribution;

            if ($fraction >= 1.0) {
                $completedCount++;
            }

            $details[] = [
                'code' => $code,
                'name' => $stage->stage_name,
                'weight' => $weight,
                'fraction' => $fraction,
                'contribution' => $contribution,
            ];
        }

        // percentage is simply accumulated weight (weights are stored as percent points)
        $percentage = $totalWeight > 0 ? min(round($accumulated, 0), 100) : 0;

        return [
            'percentage' => $percentage,
            'details' => $details,
            'completed_count' => $completedCount,
            'total_stages' => $stages->count(),
        ];
    }
}

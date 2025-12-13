<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Service\ProgressService;
use App\Models\Bimbingan;

class MonitoringController extends Controller
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by', 'nama');
        $sortDir = $request->input('sort_dir', 'asc');

        $mahasiswas = Mahasiswa::with('user')
            ->when($request->status, function ($query, $status) {
                $s = strtolower($status);
                if (in_array($s, ['aktif','non aktif','lulus'])) {
                    if ($s === 'lulus') {
                        return $query->where('status', 'like', '%lulus%');
                    }

                    if ($s === 'non aktif') {
                        return $query->where(function ($q) {
                            $q->where('status', 'like', '%non%')
                              ->orWhere('status', 'like', '%nonaktif%')
                              ->orWhere('status', 'like', '%non_aktif%');
                        });
                    }

                    // aktif: exclude lulus and common non-active flags
                    return $query->whereRaw("status NOT LIKE '%lulus%' AND status NOT LIKE '%non%' AND status IS NOT NULL");
                }

                // fallback: filter by raw status value
                return $query->where('status', $status);
            })
            ->get();

        $monitoringData = $mahasiswas->map(function ($mahasiswa) {
            if (!$mahasiswa->user) {
                return null;
            }

            $progressData = $this->progressService->getDashboardData($mahasiswa->user->id);
            
            $currentStageName = 'Selesai';
            if (!empty($progressData['details'])) {
                // Find the first stage that is not fully completed
                $firstUncompleted = collect($progressData['details'])->firstWhere('fraction', '<', 1.0);
                if ($firstUncompleted) {
                    $currentStageName = $firstUncompleted['name'];
                }
            }

            $normalized = $this->normalizeStatus($mahasiswa->status);

            return (object)[
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->user->name ?? $mahasiswa->nama,
                'progress' => $progressData['percentage'],
                'tahapan_saat_ini' => $currentStageName,
                'status' => $normalized,
                'raw_status' => $mahasiswa->status,
            ];
        })->filter();

        // Sort the final collection
        $monitoringData = $sortDir === 'asc'
            ? $monitoringData->sortBy($sortBy, SORT_NATURAL)
            : $monitoringData->sortByDesc($sortBy, SORT_NATURAL);

        // Build normalized statuses for the filter select
        $statuses = $monitoringData->pluck('status')->unique()->values();

        return view('kaprodi.monitoring', [
            'monitoringData' => $monitoringData,
            'statuses' => $statuses,
            'selectedStatus' => $request->status,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }

    /**
     * Normalize raw mahasiswa.status into one of: 'aktif', 'non aktif', 'lulus'.
     */
    private function normalizeStatus(?string $raw)
    {
        if (!$raw) {
            return 'aktif';
        }

        $raw = strtolower($raw);

        if (strpos($raw, 'lulus') !== false) {
            return 'lulus';
        }

        if (strpos($raw, 'non') !== false || strpos($raw, 'nonaktif') !== false || strpos($raw, 'non_aktif') !== false || strpos($raw, 'non-aktif') !== false) {
            return 'non aktif';
        }

        return 'aktif';
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user', 'projekAkhir');
        $progressData = $this->progressService->getDashboardData($mahasiswa->user->id);
        $bimbinganHistory = Bimbingan::where('nim', $mahasiswa->nim)->orderBy('tanggal', 'desc')->get();

        $displayStatus = $this->normalizeStatus($mahasiswa->status);

        return view('kaprodi.monitoring-detail', compact('mahasiswa', 'progressData', 'bimbinganHistory', 'displayStatus'));
    }
}

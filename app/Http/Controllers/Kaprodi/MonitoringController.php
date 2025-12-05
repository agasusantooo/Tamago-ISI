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
        $statuses = Mahasiswa::select('status')->distinct()->pluck('status');
        $sortBy = $request->input('sort_by', 'nama');
        $sortDir = $request->input('sort_dir', 'asc');

        $mahasiswas = Mahasiswa::with('user')
            ->when($request->status, function ($query, $status) {
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

            return (object)[
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->user->name ?? $mahasiswa->nama,
                'progress' => $progressData['percentage'],
                'tahapan_saat_ini' => $currentStageName,
                'status' => $mahasiswa->status,
            ];
        })->filter();

        // Sort the final collection
        $monitoringData = $sortDir === 'asc'
            ? $monitoringData->sortBy($sortBy, SORT_NATURAL)
            : $monitoringData->sortByDesc($sortBy, SORT_NATURAL);

        return view('kaprodi.monitoring', [
            'monitoringData' => $monitoringData,
            'statuses' => $statuses,
            'selectedStatus' => $request->status,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user', 'projekAkhir');
        $progressData = $this->progressService->getDashboardData($mahasiswa->user->id);
        $bimbinganHistory = Bimbingan::where('nim', $mahasiswa->nim)->orderBy('tanggal', 'desc')->get();

        return view('kaprodi.monitoring-detail', compact('mahasiswa', 'progressData', 'bimbinganHistory'));
    }
}

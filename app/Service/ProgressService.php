<?php

namespace App\Service;

class ProgressService
{
    public function getDashboardData($userId)
    {
        $completedStages = 3;
        $totalStages = 5;

        $details = [
            ['stage' => 'Proposal', 'status' => 'Selesai'],
            ['stage' => 'Bimbingan 1', 'status' => 'Selesai'],
            ['stage' => 'Bimbingan 2', 'status' => 'Berlangsung'],
            ['stage' => 'Seminar', 'status' => 'Belum dimulai'],
            ['stage' => 'Sidang', 'status' => 'Belum dimulai'],
        ];

        return [
            'percentage' => ($totalStages > 0) ? ($completedStages / $totalStages) * 100 : 0,
            'details' => $details,
            'completed_count' => $completedStages,
            'total_stages' => $totalStages,
        ];
    }
}

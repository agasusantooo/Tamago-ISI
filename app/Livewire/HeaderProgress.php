<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Service\ProgressService;

class HeaderProgress extends Component
{
    public $progress = 0; // State untuk progres bar
    protected $listeners = ['updateProgress' => 'setProgress']; // Listener untuk sinkronisasi

    protected $progressService;

    public function mount()
    {
        $this->progressService = app(ProgressService::class);
        $this->refresh();
    }

    public function setProgress($value)
    {
        $this->progress = (int) round($value);
        logger('Progress updated to: ' . $value);
    }

    /**
     * Refresh progress value for the current authenticated user.
     * Can be called from the view via Livewire polling.
     */
    public function refresh()
    {
        $userId = Auth::id();
        try {
            $data = $this->progressService->getDashboardData($userId);
            $this->progress = (int) round($data['percentage'] ?? 0);
        } catch (\Throwable $e) {
            logger('Failed to fetch progress: ' . $e->getMessage());
            $this->progress = 0;
        }
    }

    public function render()
    {
        return view('livewire.header-progress');
    }
}

<?php

namespace App\Http\Livewire\Mahasiswa;

use Livewire\Component;
use App\Service\ProgressService;
use Illuminate\Support\Facades\Auth;

class HeaderProgress extends Component
{
    public $percentage = 0;
    public $details = [];

    public function mount($initialProgress = null)
    {
        if ($initialProgress !== null) {
            $this->percentage = (int)round($initialProgress);
        } else {
            $this->refreshProgress();
        }
    }

    public function refreshProgress()
    {
        try {
            $svc = new ProgressService();
            $data = $svc->getDashboardData(Auth::id());
            $this->percentage = isset($data['percentage']) ? (int)$data['percentage'] : 0;
            $this->details = $data['details'] ?? [];
        } catch (\Exception $e) {
            // keep previous value on error
        }
    }

    // Provide a `refresh` alias for compatibility with other polling names
    public function refresh()
    {
        $this->refreshProgress();
    }

    public function render()
    {
        return view('livewire.mahasiswa.header-progress');
    }
}

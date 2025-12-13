<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Service\ProgressService;
use App\Models\Proposal;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrasi ProgressService
        $this->app->bind(ProgressService::class, function ($app) {
            return new ProgressService();
        });
    }

    public function boot(): void
    {
        // 🔸 View Composer untuk header mahasiswa
        View::composer('mahasiswa.partials.header-mahasiswa', function ($view) {
            $user = Auth::user();
            $latestProposal = null;
            $progress = 0;
            $progressDetails = [];

            if ($user) {
                $mahasiswa = $user->mahasiswa;
                $nim = $mahasiswa?->nim ?? null;
                if ($nim) {
                    $latestProposal = Proposal::where('mahasiswa_nim', $nim)
                        ->latest()
                        ->first();
                }

                // Compute progress using ProgressService so header matches dashboard
                try {
                    $progressData = app(\App\Service\ProgressService::class)->getDashboardData($user->id);
                    $progress = isset($progressData['percentage']) ? (int) max(0, min(100, $progressData['percentage'])) : 0;
                    $progressDetails = $progressData['details'] ?? [];
                } catch (\Throwable $e) {
                    // Fail silently, header should not break rendering
                    \Log::warning('ProgressService unavailable for header: ' . $e->getMessage());
                }
            }

            $view->with(compact('latestProposal', 'progress', 'progressDetails'));
        });
    }
}

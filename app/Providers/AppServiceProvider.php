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

            if ($user) {
                $mahasiswa = $user->mahasiswa;
                $nim = $mahasiswa?->nim ?? null;
                if ($nim) {
                    $latestProposal = Proposal::where('mahasiswa_nim', $nim)
                        ->latest()
                        ->first();
                }
            }

            $view->with('latestProposal', $latestProposal);
        });
    }
}

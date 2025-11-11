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
                $latestProposal = Proposal::where('mahasiswa_nim', $user->nim ?? $user->id)
                    ->latest()
                    ->first();
            }

            $view->with('latestProposal', $latestProposal);
        });
    }
}

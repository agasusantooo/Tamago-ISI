<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('mahasiswa.partials.header-mahasiswa', function ($view) {
            if (Auth::check() && Auth::user()->isMahasiswa()) {
                $latestProposal = Proposal::where('mahasiswa_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->first();
                $view->with('latestProposal', $latestProposal);
            }
        });
    }
}

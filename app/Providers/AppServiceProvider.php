<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\ProgressService;  // Ubah ini (Services → Service)

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProgressService::class, function ($app) {
            return new ProgressService();
        });
    }

    public function boot(): void
    {
        //
    }
}
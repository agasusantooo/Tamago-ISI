<?php

namespace App\Providers;

use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            $latestProposal = null;
            if (Auth::check() && Auth::user()->isMahasiswa()) {
                $mahasiswa = Auth::user()->mahasiswa;
                $latestProposal = $mahasiswa ? Proposal::where('mahasiswa_nim', $mahasiswa->nim)
                    ->orderBy('created_at', 'desc')
                    ->first() : null;
            }
            $view->with('latestProposal', $latestProposal);
        });

        // Global schema readiness checks, useful for showing warnings or hiding features
        $schema = Schema::getConnection()->getSchemaBuilder();
        $checks = [];
        $checks['dosen_penguji_ready'] = $schema->hasTable('ujian_tugas_akhir') &&
            $schema->hasColumn('ujian_tugas_akhir', 'ketua_penguji_id') &&
            $schema->hasColumn('ujian_tugas_akhir', 'penguji_ahli_id');

        $checks['bimbingan_min_updated'] = $schema->hasTable('ta_progress_stages') &&
            $schema->hasColumn('ta_progress_stages', 'type');

        View::share('schemaChecks', $checks);
    }
}

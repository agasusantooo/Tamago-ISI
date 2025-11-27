<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Minimal index method to satisfy route reflection in artisan commands.
     */
    public function index()
    {
        // Return a simple view if exists; if not, just return a string to avoid errors
        if (view()->exists('admin.dashboard')) {
            // Get user counts by role
            $totalMahasiswa = User::whereHas('role', function ($q) {
                $q->where('name', 'mahasiswa');
            })->count();

            $totalDosen = User::whereHas('role', function ($q) {
                $q->where('name', 'dospem');
            })->count();

            $totalKorprodi = User::whereHas('role', function ($q) {
                $q->where('name', 'kaprodi');
            })->count();

            $totalAdmin = User::whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->count();

            // Mock data for activities and notifications (replace with real data if models exist)
            $aktivitasTerakhir = collect();
            $notifikasiPenting = collect();

            return view('admin.dashboard', compact(
                'totalMahasiswa',
                'totalDosen',
                'totalKorprodi',
                'totalAdmin',
                'aktivitasTerakhir',
                'notifikasiPenting'
            ));
        }

        return 'Admin dashboard placeholder';
    }
}

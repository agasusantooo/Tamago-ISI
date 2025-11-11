<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            return view('admin.dashboard');
        }

        return 'Admin dashboard placeholder';
    }
}

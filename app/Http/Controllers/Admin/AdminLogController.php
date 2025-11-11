<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index()
    {
        if (view()->exists('admin.logs')) {
            return view('admin.logs');
        }

        return 'Admin logs placeholder';
    }
}

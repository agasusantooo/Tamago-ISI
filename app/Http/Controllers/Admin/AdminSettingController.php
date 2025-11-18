<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        if (view()->exists('admin.settings')) {
            return view('admin.settings');
        }

        return 'Admin settings placeholder';
    }
}

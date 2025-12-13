<?php

namespace App\Http\Controllers\KoordinatorTefa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('koordinator_tefa.dashboard');
    }
}

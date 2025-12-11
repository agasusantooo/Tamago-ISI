<?php

namespace App\Http\Controllers\KoordinatorStoryConference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('koordinator_story_conference.dashboard');
    }
}

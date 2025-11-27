<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function detail()
    {
        // Logic to show laporan detail
        return view('mahasiswa.laporan.detail');
    }
}

<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function detail()
    {
        // Logic to show laporan detail
        return view('mahasiswa.laporan.detail');
    }
}

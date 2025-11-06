<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Bimbingan;
use App\Models\ProjekAkhir;
use Illuminate\Support\Facades\Auth;

class BimbinganMahasiswa extends Component
{
    public $tanggal;
    public $catatan_bimbingan;
    public $pencapaian;
    public $pembimbings = [];
    public $selectedNidn;

    public function mount()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();
        if ($projekAkhir) {
            $this->pembimbings = $projekAkhir->pembimbing();
        }
    }

    public function store()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'catatan_bimbingan' => 'required|string',
            'pencapaian' => 'required|string',
            'selectedNidn' => 'required',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();

        Bimbingan::create([
            'id_proyek_akhir' => $projekAkhir->id_proyek_akhir,
            'nidn' => $this->selectedNidn,
            'tanggal' => $this->tanggal,
            'catatan_bimbingan' => $this->catatan_bimbingan,
            'pencapaian' => $this->pencapaian,
            'status_persetujuan' => 'pending',
        ]);

        session()->flash('message', 'Pengajuan bimbingan berhasil dikirim.');

        $this->reset();
    }

    public function render()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();
        $bimbingans = $projekAkhir ? $projekAkhir->bimbingan()->get() : collect();

        return view('livewire.mahasiswa.bimbingan-mahasiswa', compact('bimbingans'));
    }
}

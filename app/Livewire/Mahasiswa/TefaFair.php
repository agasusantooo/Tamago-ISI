<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\TefaFair as TefaFairModel;
use App\Models\ProjekAkhir;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class TefaFair extends Component
{
    use WithFileUploads;

    public $semester;
    public $file_presentasi;
    public $daftar_kebutuhan;
    public $tefaFair;

    public function mount()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if ($mahasiswa) {
            $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();
            $this->tefaFair = $projekAkhir ? $projekAkhir->tefaFair()->latest()->first() : null;
        }
    }

    public function store()
    {
        $this->validate([
            'semester' => 'required|string',
            'file_presentasi' => 'required|file|mimes:pdf,ppt,pptx',
            'daftar_kebutuhan' => 'required|string',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();

        $filePath = $this->file_presentasi->store('tefa-fair');

        TefaFairModel::create([
            'id_proyek_akhir' => $projekAkhir->id_proyek_akhir,
            'semester' => $this->semester,
            'file_presentasi' => $filePath,
            'daftar_kebutuhan' => $this->daftar_kebutuhan,
        ]);

        session()->flash('message', 'Pendaftaran Tefa Fair berhasil dikirim.');

        $this->reset();
        $this->mount();
    }

    public function render()
    {
        $tefaFairs = TefaFairModel::with('projekAkhir.mahasiswa')->get();
        return view('livewire.mahasiswa.tefa-fair', compact('tefaFairs'));
    }
}

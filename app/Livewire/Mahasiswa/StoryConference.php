<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\StoryConference as StoryConferenceModel;
use App\Models\ProjekAkhir;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class StoryConference extends Component
{
    use WithFileUploads;

    public $waktu;
    public $file;
    public $storyConference;

    public function mount()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if ($mahasiswa) {
            $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();
            $this->storyConference = $projekAkhir ? $projekAkhir->storyConference()->latest()->first() : null;
        }
    }

    public function store()
    {
        $this->validate([
            'waktu' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $projekAkhir = $mahasiswa->projekAkhir()->latest()->first();

        $filePath = $this->file->store('story-conference');

        StoryConferenceModel::create([
            'id_proyek_akhir' => $projekAkhir->id_proyek_akhir,
            'tanggal' => now(),
            'waktu' => $this->waktu,
            'file' => $filePath,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Pendaftaran Story Conference berhasil dikirim.');

        $this->reset();
        $this->mount();
    }

    public function render()
    {
        $storyConferences = StoryConferenceModel::with('projekAkhir.mahasiswa')->get();
        return view('livewire.mahasiswa.story-conference', compact('storyConferences'));
    }
}

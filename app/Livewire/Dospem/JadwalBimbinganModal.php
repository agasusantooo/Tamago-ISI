<?php

namespace App\Livewire\Dospem;

use Livewire\Component;
use App\Models\Jadwal;
use App\Models\Mahasiswa;

class JadwalBimbinganModal extends Component
{
    public $showModal = false;
    public $jadwalId = null;
    public $jadwal = null;
    public $confirmAction = null;
    public $actionMessage = '';

    protected $listeners = ['openJadwalModal'];

    public function openJadwalModal($jadwalId)
    {
        $this->jadwalId = $jadwalId;
        $this->jadwal = Jadwal::with(['mahasiswa', 'dosen'])->find($jadwalId);
        $this->showModal = true;
        $this->confirmAction = null;
        $this->actionMessage = '';
    }

    public function approveBimbingan()
    {
        if (!$this->jadwal) return;

        try {
            $this->jadwal->update([
                'status' => 'disetujui',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Update mahasiswa status to reflect jadwal approval
            if ($this->jadwal->mahasiswa_id) {
                $mahasiswa = Mahasiswa::where('user_id', $this->jadwal->mahasiswa_id)->first();
                if ($mahasiswa) {
                    $mahasiswa->update(['status' => 'jadwal_disetujui']);
                }
            }

            $this->dispatch('jadwalApproved', ['jadwalId' => $this->jadwalId]);
            $this->showModal = false;

            session()->flash('success', 'Jadwal bimbingan berhasil disetujui!');
        } catch (\Exception $e) {
            $this->addError('approval', 'Gagal menyetujui jadwal: ' . $e->getMessage());
        }
    }

    public function rejectBimbingan()
    {
        if (!$this->jadwal) return;

        try {
            $this->jadwal->update([
                'status' => 'ditolak',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $this->actionMessage,
            ]);

            // Update mahasiswa status to reflect jadwal rejection
            if ($this->jadwal->mahasiswa_id) {
                $mahasiswa = Mahasiswa::where('user_id', $this->jadwal->mahasiswa_id)->first();
                if ($mahasiswa) {
                    $mahasiswa->update(['status' => 'jadwal_ditolak']);
                }
            }

            $this->dispatch('jadwalRejected', ['jadwalId' => $this->jadwalId]);
            $this->showModal = false;

            session()->flash('success', 'Jadwal bimbingan berhasil ditolak!');
        } catch (\Exception $e) {
            $this->addError('rejection', 'Gagal menolak jadwal: ' . $e->getMessage());
        }
    }

    public function setAction($action)
    {
        $this->confirmAction = $action;
    }

    public function cancelAction()
    {
        $this->confirmAction = null;
        $this->actionMessage = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->confirmAction = null;
        $this->actionMessage = '';
    }

    public function render()
    {
        return view('livewire.dospem.jadwal-bimbingan-modal');
    }
}

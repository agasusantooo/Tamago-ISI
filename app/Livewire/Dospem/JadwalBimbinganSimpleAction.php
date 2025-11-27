<?php

namespace App\Livewire\Dospem;

use Livewire\Component;
use App\Models\Jadwal;

/**
 * ALTERNATIF SIMPLE: Dialog Konfirmasi untuk ACC/Tolak Jadwal
 * 
 * Jika ingin lebih simple tanpa Livewire modal, bisa gunakan native dialog
 * atau simple confirm dialog ini.
 * 
 * Usage:
 * <button wire:click="confirmApprove({{ $jadwal->id }})">
 *     Approve
 * </button>
 */

class JadwalBimbinganSimpleAction extends Component
{
    public $jadwalId = null;
    public $rejectReason = '';
    public $showConfirm = false;
    public $confirmAction = null;

    protected $listeners = [
        'confirmApprove',
        'confirmReject',
    ];

    public function confirmApprove($jadwalId)
    {
        $this->jadwalId = $jadwalId;
        $this->confirmAction = 'approve';
        $this->showConfirm = true;
    }

    public function confirmReject($jadwalId)
    {
        $this->jadwalId = $jadwalId;
        $this->confirmAction = 'reject';
        $this->showConfirm = true;
    }

    public function proceed()
    {
        if (!$this->jadwalId) return;

        $jadwal = Jadwal::find($this->jadwalId);
        if (!$jadwal) {
            $this->showConfirm = false;
            return;
        }

        try {
            if ($this->confirmAction === 'approve') {
                $jadwal->update([
                    'status' => 'disetujui',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                session()->flash('success', '✓ Jadwal bimbingan berhasil disetujui!');
            } else {
                $jadwal->update([
                    'status' => 'ditolak',
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id(),
                    'rejection_reason' => $this->rejectReason,
                ]);
                session()->flash('success', '✗ Jadwal bimbingan berhasil ditolak!');
            }

            $this->dispatch('jadwalUpdated');
            $this->reset(['jadwalId', 'rejectReason', 'showConfirm', 'confirmAction']);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->reset(['jadwalId', 'rejectReason', 'showConfirm', 'confirmAction']);
    }

    public function render()
    {
        return view('livewire.dospem.jadwal-bimbingan-simple-action');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Produksi;
use Carbon\Carbon;

class FinalizationFlow extends Component
{
    public $produksiId;
    public $produksi;

    protected $listeners = ['produksiUpdated' => 'loadProduksi'];

    public function mount($produksiId = null)
    {
        $this->produksiId = $produksiId;
        \Log::info('FinalizationFlow mounted', ['produksiId' => $produksiId]);
        $this->loadProduksi();
    }

    public function loadProduksi()
    {
        if ($this->produksiId) {
            $this->produksi = Produksi::find($this->produksiId);
        } else {
            $this->produksi = null;
        }
    }

    public function saveDraft()
    {
        \Log::info('FinalizationFlow.saveDraft called', ['produksiId' => $this->produksiId]);
        if (!$this->produksi) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Data produksi tidak ditemukan']);
            return;
        }

        $this->produksi->tanggal_upload_akhir = Carbon::now();
        $this->produksi->save();

        $this->loadProduksi();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Draft disimpan']);
        $this->emit('produksiUpdated');
    }

    public function submitVerification()
    {
        \Log::info('FinalizationFlow.submitVerification called', ['produksiId' => $this->produksiId]);
        if (!$this->produksi) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Data produksi tidak ditemukan']);
            return;
        }

        if (empty($this->produksi->file_produksi_akhir)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Unggah file final dulu sebelum mengajukan verifikasi']);
            return;
        }

        $this->produksi->status_produksi = 'menunggu_review';
        $this->produksi->tanggal_upload_akhir = Carbon::now();
        $this->produksi->save();

        $this->loadProduksi();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Pengajuan verifikasi terkirim']);
        $this->emit('produksiUpdated');
    }

    public function render()
    {
        return view('livewire.finalization-flow', [
            'produksi' => $this->produksi,
        ]);
    }
}

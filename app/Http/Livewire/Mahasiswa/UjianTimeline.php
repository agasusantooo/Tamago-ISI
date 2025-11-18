<?php

namespace App\Http\Livewire\Mahasiswa;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ProjekAkhir;
use App\Models\UjianTA;
use App\Models\Produksi;

class UjianTimeline extends Component
{
    public $timeline = [];
    public $status = null;
    public $dates = [];
    public $hasUjian = false;
    public $ujianStatus = null;
    public $selectedStatus = null;
    // statuses that can be set by authorised users
    public $allowedStatuses = [
        'belum_upload', 'menunggu_review', 'disetujui', 'revisi', 'ditolak',
        // existing workflow statuses
        'pengajuan_ujian', 'jadwal_ditetapkan', 'ujian_berlangsung', 'selesai_ujian'
    ];
    // optionally accept projek and ujian passed from parent view for initial sync
    public $projek = null;
    public $ujianTA = null;
    // accept ids from parent to avoid model serialization issues
    public $projekId = null;
    public $ujianId = null;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Default empty timeline
        $timeline = [];
        // Prefer ids passed from parent view (avoids Livewire model serialization/timing issues).
        if ($this->projekId) {
            $projek = ProjekAkhir::where('id_proyek_akhir', $this->projekId)->first();
        } else {
            $projek = $this->projek ?? ($mahasiswa ? ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first() : null);
        }

        if ($this->ujianId) {
            $ujian = UjianTA::where('id_ujian', $this->ujianId)->first();
        } else {
            $ujian = $this->ujianTA ?? ($projek ? UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first() : null);
        }

        // expose the ujian model to the blade view
        $this->ujianTA = $ujian;
        $this->selectedStatus = $ujian->status_pendaftaran ?? null;

    $this->hasUjian = (bool)$ujian;
    $this->ujianStatus = $ujian->status_ujian ?? null;

        // Check produksi approval
        $produksi = Produksi::where('mahasiswa_id', $user->id)->latest()->first();

        // Build timeline items (use dates if available)
        if ($ujian) {
            $timeline[] = [
                'title' => 'Pengajuan Ujian',
                'date' => optional($ujian->tanggal_daftar)->format('d M Y') ?? '—',
                'color' => 'green',
            ];
            if ($ujian->status_pendaftaran === 'jadwal_ditetapkan' || $ujian->status_ujian !== 'belum_ujian') {
                $timeline[] = [
                    'title' => 'Jadwal Ditetapkan',
                    'date' => optional($ujian->tanggal_ujian)->format('d M Y') ?? '—',
                    'color' => 'green',
                ];
            }
            if ($ujian->status_ujian === 'ujian_berlangsung' || $ujian->status_ujian === 'selesai_ujian') {
                $timeline[] = [
                    'title' => 'Ujian Berlangsung',
                    'date' => optional($ujian->tanggal_ujian)->format('d M Y') ?? '—',
                    'color' => 'blue',
                ];
            }
            $timeline[] = [
                'title' => 'Revisi Selesai',
                'date' => $ujian->status_revisi === 'revisi_selesai' ? optional($ujian->tanggal_approve_revisi)->format('d M Y') ?? '—' : 'Pending',
                'color' => $ujian->status_revisi === 'revisi_selesai' ? 'green' : 'gray',
            ];
            $this->status = [
                'text' => 'Status: ' . ($ujian->status_pendaftaran ? ucwords(str_replace('_',' ',$ujian->status_pendaftaran)) : 'Belum Daftar'),
                'variant' => $ujian->status_pendaftaran === 'pengajuan_ujian' ? 'yellow' : 'green',
            ];
        } else {
            // Fallback: show produksi status if no ujian registered yet
            $timeline = [
                ['title' => 'Pengajuan Ujian', 'date' => '—', 'color' => 'green'],
                ['title' => 'Jadwal Ditetapkan', 'date' => '—', 'color' => 'gray'],
                ['title' => 'Ujian Berlangsung', 'date' => '—', 'color' => 'gray'],
                ['title' => 'Revisi Selesai', 'date' => 'Pending', 'color' => 'gray'],
            ];

            if ($produksi && $produksi->status_produksi_akhir === 'disetujui') {
                $this->status = ['text' => 'Produksi akhir disetujui', 'variant' => 'green'];
            } else {
                $this->status = ['text' => 'Menunggu Persetujuan', 'variant' => 'yellow'];
            }
        }

        $this->timeline = $timeline;
    }

    /**
     * Update status_pendaftaran for the current ujianTA.
     * Only authorised users (admin, dosen roles) may perform this.
     */
    public function updateStatus()
    {
        try {
            $user = Auth::user();
            // basic role checks - allow admins and any teaching staff roles
            $allowed = $user && (
                $user->isAdmin() || $user->isDospem() || $user->isDosenPenguji() || $user->isKaprodi() || $user->isKoordinatorTA()
            );

            if (! $allowed) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah status.']);
                return;
            }

            if (! $this->ujianTA) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Tidak ada pendaftaran ujian yang dipilih.']);
                return;
            }

            if (! in_array($this->selectedStatus, $this->allowedStatuses)) {
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Status yang dipilih tidak valid.']);
                return;
            }

            $this->ujianTA->update(['status_pendaftaran' => $this->selectedStatus]);
            $this->refreshData();
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Status pendaftaran berhasil diubah.']);
        } catch (\Exception $e) {
            Log::error('Failed to update ujian status', ['error' => $e->getMessage(), 'user_id' => optional(Auth::user())->id]);
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan status.']);
        }
    }

    public function render()
    {
        // refresh each render to pick up DB changes (also wire:poll will call render periodically)
        $this->refreshData();
        return view('livewire.mahasiswa.ujian-timeline');
    }
}

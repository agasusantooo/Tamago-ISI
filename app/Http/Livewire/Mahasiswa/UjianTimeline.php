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
    public $ujianStatusPendaftaran = null;
    public $ujianTanggalDaftar = null;
    // statuses that can be set by authorised users
    public $allowedStatuses = [
        'belum_upload', 'menunggu_review', 'disetujui', 'revisi', 'ditolak',
        // existing workflow statuses
        'pengajuan_ujian', 'jadwal_ditetapkan', 'ujian_berlangsung', 'selesai_ujian'
    ];
    // accept ids from parent to avoid model serialization issues
    public $projekId = null;
    public $ujianId = null;
    
    // Private properties (not tracked by Livewire) for storing models
    private $ujianTA = null;
    private $projek = null;

    public function mount()
    {
        Log::debug('UjianTimeline mount', [
            'projekId' => $this->projekId,
            'ujianId' => $this->ujianId,
            'user_id' => Auth::id(),
        ]);
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Prefer ids passed from parent view (avoids Livewire model serialization/timing issues).
        if ($this->projekId) {
            $this->projek = ProjekAkhir::where('id_proyek_akhir', $this->projekId)->first();
        } else {
            $this->projek = $mahasiswa ? ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first() : null;
        }

        // Always query fresh ujian from DB based on projek, even if ujianId was null before.
        // This ensures newly created ujian records are picked up on poll refresh.
        $this->ujianTA = null;
        if ($this->projek) {
            $this->ujianTA = UjianTA::where('id_proyek_akhir', $this->projek->id_proyek_akhir)->latest()->first();
        }

        // Debug: log current state
        Log::debug('UjianTimeline refreshData', [
            'has_projek' => (bool)$this->projek,
            'projek_id' => $this->projek?->id_proyek_akhir,
            'has_ujian' => (bool)$this->ujianTA,
            'ujian_id' => $this->ujianTA?->id_ujian,
            'status_pendaftaran' => $this->ujianTA?->status_pendaftaran,
            'status_ujian' => $this->ujianTA?->status_ujian,
        ]);

        // Extract scalar values from model for serialization
        if ($this->ujianTA) {
            $this->ujianStatusPendaftaran = $this->ujianTA->status_pendaftaran;
            $this->ujianTanggalDaftar = $this->ujianTA->tanggal_daftar ? $this->ujianTA->tanggal_daftar->format('d M Y') : '—';
            $this->selectedStatus = $this->ujianTA->status_pendaftaran;
            $this->hasUjian = true;
            $this->ujianStatus = $this->ujianTA->status_ujian;
        } else {
            $this->ujianStatusPendaftaran = null;
            $this->ujianTanggalDaftar = '—';
            $this->selectedStatus = null;
            $this->hasUjian = false;
            $this->ujianStatus = null;
        }

        // Check produksi approval
        $produksi = Produksi::where('mahasiswa_id', $user->id)->latest()->first();

        // Build timeline items (use dates if available)
        $timelineItems = [];
        if ($this->ujianTA) {
            // Always show "Pengajuan Ujian" as first item when ujian exists
            $timelineItems[] = [
                'title' => 'Pengajuan Ujian',
                'date' => $this->ujianTanggalDaftar,
                'color' => 'green',
            ];
            
            // Get raw DB values for comparison - normalize the strings
            $statusPendaftaran = strtolower(str_replace([' ', '-', '_'], '', $this->ujianTA->status_pendaftaran ?? ''));
            $statusUjian = strtolower(str_replace([' ', '-', '_'], '', $this->ujianTA->status_ujian ?? ''));

            // Check if jadwal ditetapkan status reached
            if (strpos($statusPendaftaran, 'jadwal') !== false ||
                strpos($statusPendaftaran, 'ujianberlangsung') !== false ||
                strpos($statusUjian, 'berlangsung') !== false ||
                strpos($statusUjian, 'selesai') !== false) {
                $tanggalUjian = $this->ujianTA->tanggal_ujian ? $this->ujianTA->tanggal_ujian->format('d M Y') : '—';
                $timelineItems[] = [
                    'title' => 'Jadwal Ditetapkan',
                    'date' => $tanggalUjian,
                    'color' => 'green',
                ];
            }

            // Check if ujian berlangsung or selesai
            if (strpos($statusPendaftaran, 'ujianberlangsung') !== false ||
                strpos($statusUjian, 'berlangsung') !== false ||
                strpos($statusUjian, 'selesai') !== false) {
                $tanggalUjian = $this->ujianTA->tanggal_ujian ? $this->ujianTA->tanggal_ujian->format('d M Y') : '—';
                $timelineItems[] = [
                    'title' => 'Ujian Berlangsung',
                    'date' => $tanggalUjian,
                    'color' => strpos($statusUjian, 'selesai') !== false ? 'green' : 'blue',
                ];
            }
            
            // Revisi status
            $timelineItems[] = [
                'title' => 'Revisi Selesai',
                'date' => (strpos(strtolower($this->ujianTA->status_revisi ?? ''), 'selesai') !== false) ? $this->ujianTA->tanggal_approve_revisi?->format('d M Y') ?? '—' : 'Pending',
                'color' => (strpos(strtolower($this->ujianTA->status_revisi ?? ''), 'selesai') !== false) ? 'green' : 'gray',
            ];
            
            $this->status = [
                'text' => 'Status: ' . str_replace(['_'], ' ', ucfirst($this->ujianTA->status_pendaftaran ?? 'Tidak ada status')),
                'variant' => strpos($statusPendaftaran, 'pengajuan') !== false ? 'yellow' : 'green',
            ];
        } else {
            // Fallback: show produksi status if no ujian registered yet
            $timelineItems = [
                ['title' => 'Pengajuan Ujian', 'date' => '—', 'color' => 'green'],
                ['title' => 'Jadwal Ditetapkan', 'date' => '—', 'color' => 'gray'],
                ['title' => 'Ujian Berlangsung', 'date' => '—', 'color' => 'gray'],
                ['title' => 'Revisi Selesai', 'date' => 'Pending', 'color' => 'gray'],
            ];

            if ($produksi && $produksi->status_produksi === 'disetujui') {
                $this->status = ['text' => 'Produksi akhir disetujui', 'variant' => 'green'];
            } else {
                $this->status = ['text' => 'Menunggu Persetujuan', 'variant' => 'yellow'];
            }
        }

        $this->timeline = $timelineItems;
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
        
        // Build data for view - use ONLY scalar values, NOT Eloquent models
        $viewData = [
            'timeline' => $this->timeline,
            'status' => $this->status,
            'hasUjian' => $this->hasUjian,
            'ujianStatus' => $this->ujianStatus,
            'ujianStatusPendaftaran' => $this->ujianStatusPendaftaran,
            'ujianTanggalDaftar' => $this->ujianTanggalDaftar,
            'allowedStatuses' => $this->allowedStatuses,
            'selectedStatus' => $this->selectedStatus,
        ];
        
        return view('livewire.mahasiswa.ujian-timeline', $viewData);
    }
}

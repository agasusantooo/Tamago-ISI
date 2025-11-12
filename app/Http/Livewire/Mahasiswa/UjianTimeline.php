<?php

namespace App\Http\Livewire\Mahasiswa;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
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

        // Find projek and ujian
        $projek = $mahasiswa ? ProjekAkhir::where('nim', $mahasiswa->nim)->latest()->first() : null;
        $ujian = $projek ? UjianTA::where('id_proyek_akhir', $projek->id_proyek_akhir)->latest()->first() : null;

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

    public function render()
    {
        // refresh each render to pick up DB changes (also wire:poll will call render periodically)
        $this->refreshData();
        return view('livewire.mahasiswa.ujian-timeline');
    }
}

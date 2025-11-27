<!-- CONTOH IMPLEMENTASI JADWAL BIMBINGAN MODAL -->

<!-- 1. Tambahkan ke view yang menampilkan list jadwal -->
<!-- resources/views/dospem/jadwal-bimbingan.blade.php -->

<div class="flex h-screen overflow-hidden">
    @include('dospem.partials.sidebar-dospem')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('dospem.partials.header-dospem')

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto">
                <!-- TAB NAVIGATION -->
                <div class="mb-6 flex space-x-2 border-b border-gray-200">
                    <button onclick="switchJadwalTab('list')" class="jadwal-tab-btn px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-medium" data-tab="list">
                        <i class="fas fa-list mr-2"></i>Daftar Permintaan
                    </button>
                </div>

                <!-- TAB: LIST VIEW -->
                <div id="list-tab" class="jadwal-tab-content bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Daftar Permintaan Jadwal Bimbingan</h3>
                    
                    <!-- Filter buttons -->
                    <div class="mb-4 flex space-x-2">
                        <button onclick="filterJadwalStatus('all')" class="filter-btn px-4 py-2 rounded-md text-sm bg-blue-100 text-blue-700 font-medium border border-blue-300" data-status="all">
                            Semua
                        </button>
                        <button onclick="filterJadwalStatus('menunggu')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:border-yellow-300" data-status="menunggu">
                            Menunggu
                        </button>
                        <button onclick="filterJadwalStatus('disetujui')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:border-green-300" data-status="disetujui">
                            Disetujui
                        </button>
                        <button onclick="filterJadwalStatus('ditolak')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:border-red-300" data-status="ditolak">
                            Ditolak
                        </button>
                    </div>

                    <!-- List of jadwal requests -->
                    <div id="jadwal-list" class="space-y-3">
                        @forelse($jadwals as $jadwal)
                            <div class="border rounded-lg p-4 bg-white hover:bg-gray-50 transition flex justify-between items-start" data-status="{{ $jadwal->status }}">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $jadwal->mahasiswa->nama ?? 'Mahasiswa' }}
                                            <span class="text-xs text-gray-500">({{ $jadwal->mahasiswa->nim ?? '-' }})</span>
                                        </h4>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($jadwal->status === 'disetujui')
                                                bg-green-100 text-green-800
                                            @elseif($jadwal->status === 'ditolak')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-yellow-100 text-yellow-800
                                            @endif
                                        ">
                                            {{ ucfirst($jadwal->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        {{ $jadwal->tanggal?->format('d M Y') }} Pukul {{ date('H:i', strtotime($jadwal->jam_mulai ?? '10:00')) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-book mr-2"></i>{{ $jadwal->topik ?? 'Bimbingan' }}
                                    </p>
                                </div>
                                <div class="ml-4 flex space-x-2">
                                    @if($jadwal->status === 'menunggu')
                                        <button wire:click="$dispatch('openJadwalModal', { jadwalId: {{ $jadwal->id }} })" class="px-3 py-2 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                            <i class="fas fa-check-circle mr-1"></i>Review
                                        </button>
                                    @else
                                        <button wire:click="$dispatch('openJadwalModal', { jadwalId: {{ $jadwal->id }} })" class="px-3 py-2 text-xs bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada jadwal bimbingan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAMBAHKAN LIVEWIRE COMPONENT DI SINI -->
            <livewire:dospem.jadwal-bimbingan-modal />
        </main>
    </div>
</div>

<!-- IMPLEMENTASI DALAM CONTROLLER -->
<!-- app/Http/Controllers/Dospem/JadwalBimbinganController.php -->

<?php

namespace App\Http\Controllers\Dospem;

use Illuminate\Routing\Controller;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class JadwalBimbinganController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $jadwals = Jadwal::with(['mahasiswa', 'dosen'])
            ->where('dosen_id', $dosen->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dospem.jadwal-bimbingan', [
            'jadwals' => $jadwals,
            'mahasiswaAktifCount' => $dosen->mahasiswa_bimbingan()->active()->count(),
            'tugasReview' => 0, // Dari data yang relevan
        ]);
    }

    // Action untuk approve jadwal (via Livewire component)
    public function approve(Jadwal $jadwal)
    {
        // Logic sudah ada di JadwalBimbinganModal component
    }

    // Action untuk reject jadwal (via Livewire component)
    public function reject(Jadwal $jadwal)
    {
        // Logic sudah ada di JadwalBimbinganModal component
    }
}
?>

<!-- KONFIGURASI DATABASE -->
<!-- Pastikan tabel jadwal memiliki kolom: -->
<!-- - id -->
<!-- - mahasiswa_id -->
<!-- - dosen_id -->
<!-- - tanggal (date) -->
<!-- - jam_mulai (time) -->
<!-- - jam_selesai (time) -->
<!-- - tempat (string) -->
<!-- - topik (text) -->
<!-- - status (enum: menunggu, disetujui, ditolak) -->
<!-- - approved_at (timestamp, nullable) -->
<!-- - approved_by (unsignedBigInteger, nullable) -->
<!-- - rejected_at (timestamp, nullable) -->
<!-- - rejected_by (unsignedBigInteger, nullable) -->
<!-- - rejection_reason (text, nullable) -->
<!-- - created_at, updated_at -->

<!-- FITUR MODAL: -->
<!-- 1. Tampilkan detail jadwal bimbingan -->
<!-- 2. Tombol ACC (hijau) dan Tolak (merah) -->
<!-- 3. Konfirmasi sebelum ACC -->
<!-- 4. Field opsional untuk alasan penolakan -->
<!-- 5. Notifikasi after action (success/error) -->
<!-- 6. Responsive dan user-friendly -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Partial -->
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header Partial -->
            @include('mahasiswa.partials.header-mahasiswa', ['pageTitle' => 'Dashboard'])

            <!-- Main Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    Selamat datang, <span class="uppercase">{{ Auth::user()->name }}</span> 👋
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">NIM: {{ Auth::user()->mahasiswa->nim ?? 'N/A' }}</p>
                            </div>

                            <!-- Progres Tugas Akhir -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">Progres Tugas Akhir</h3>
                                        <button onclick="toggleProgressDetail()" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                                            <i class="fas fa-list mr-1"></i>Detail
                                        </button>
                                    </div>
                                    <div class="flex justify-center items-center">
                                        @php
                                            $pct = isset($progress) ? (int)$progress : 0;
                                            $radius = 45;
                                            $circ = 2 * pi() * $radius;
                                            $dashOffset = $circ * (1 - ($pct / 100));
                                        @endphp
                                        <div class="relative w-40 h-40">
                                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                                <g transform="translate(50,50)">
                                                    <circle r="{{ $radius }}" fill="none" stroke="#e5e7eb" stroke-width="8" />
                                                    <circle r="{{ $radius }}" fill="none" stroke="#FACC15" stroke-width="8"
                                                        stroke-dasharray="{{ $circ }}"
                                                        stroke-dashoffset="{{ $dashOffset }}"
                                                        stroke-linecap="round"
                                                        transform="rotate(-90)"
                                                    />
                                                </g>
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-3xl font-bold text-yellow-600">{{ $pct }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-3"></div>
                                    
                                    <!-- Detail Progress Breakdown -->
                                    <div id="progressDetail" class="mt-6 pt-6 border-t border-gray-200 hidden">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Rincian Tahapan Progress</h4>
                                        <div class="space-y-3">
                                            @forelse($progressDetails as $detail)
                                                @php
                                                    $actionRoute = null;
                                                    $actionLabel = 'Buka';
                                                    $code = $detail['code'] ?? null;
                                                    switch($code) {
                                                        case 'proposal_submission':
                                                            $actionRoute = route('mahasiswa.proposal.create');
                                                            $actionLabel = 'Ajukan Proposal';
                                                            break;
                                                        case 'proposal_approved':
                                                            $actionRoute = route('mahasiswa.proposal.index');
                                                            $actionLabel = 'Lihat Proposal';
                                                            break;
                                                        case 'bimbingan_progress':
                                                            $actionRoute = route('mahasiswa.bimbingan.index');
                                                            $actionLabel = 'Bimbingan';
                                                            break;
                                                        case 'story_conference':
                                                            $actionRoute = route('mahasiswa.story-conference.create');
                                                            $actionLabel = 'Ajukan Story Conference';
                                                            break;
                                                        case 'production_upload':
                                                            $actionRoute = route('mahasiswa.produksi.manage');
                                                            $actionLabel = 'Upload Produksi';
                                                            break;
                                                        case 'exam_registration':
                                                        case 'exam_completed':
                                                            $actionRoute = route('mahasiswa.ujian-ta.index');
                                                            $actionLabel = 'Ujian TA';
                                                            break;
                                                        case 'final_submission':
                                                            $actionRoute = route('mahasiswa.produksi.manage');
                                                            $actionLabel = 'Selesai & Upload';
                                                            break;
                                                        default:
                                                            $actionRoute = null;
                                                    }
                                                @endphp

                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-800">{{ $detail['name'] }}</p>
                                                        <div class="mt-1 bg-gray-200 rounded-full h-2 w-full">
                                                            <div class="bg-yellow-500 h-2 rounded-full transition-all duration-500" 
                                                                 style="width: {{ min($detail['fraction'] * 100, 100) }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3 text-right flex flex-col items-end">
                                                        <p class="text-xs text-gray-600">Bobot: {{ (int)$detail['weight'] }}%</p>
                                                        <p class="text-xs font-semibold text-gray-800">{{ (int)($detail['fraction'] * 100) }}%</p>
                                                        @if($actionRoute)
                                                            <a href="{{ $actionRoute }}" class="mt-2 inline-block px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">{{ $actionLabel }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-xs text-gray-500">Tidak ada data progress</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Dosen -->
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dosen Pembimbing</h3>
                                    <div class="space-y-3">
                                        @if($dosenPembimbing)
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full font-bold">
                                                    {{ strtoupper(substr($dosenPembimbing->nama, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $dosenPembimbing->nama }}</p>
                                                    <p class="text-xs text-gray-500">Pembimbing Utama</p>
                                                </div>
                                            </div>
                                            <div class="space-y-1 text-sm">
                                                <div class="flex items-center space-x-2 text-gray-600">
                                                    <i class="fas fa-envelope w-4"></i>
                                                    <span>{{ optional($dosenPembimbing->user)->email ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500">Belum ada dosen pembimbing</p>
                                        @endif
                                        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="w-full mt-3 inline-block text-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition">
                                            <i class="fas fa-calendar-alt mr-2"></i>Buat Jadwal Bimbingan
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas & Deadline -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tugas & Deadline Mendatang</h3>
                                <div class="space-y-3">
                                    @forelse($upcomingDeadlines as $deadline)
                                        <div class="flex items-center justify-between p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ optional($deadline->taProgressStage)->name ?? 'Deadline' }}</p>
                                                <p class="text-xs text-yellow-700 mt-1">
                                                    <i class="far fa-clock mr-1"></i>Deadline: {{ $deadline->due_date ? $deadline->due_date->format('d M Y') : 'N/A' }}
                                                </p>
                                                @if($deadline->semester)
                                                    <p class="text-xs text-gray-600 mt-1">{{ $deadline->semester->nama }}</p>
                                                @endif
                                            </div>
                                            @php
                                                $stage = optional($deadline->taProgressStage);
                                                $stageCode = $stage->stage_code ?? null;
                                                $deadlineRoute = route('mahasiswa.proposal.index');
                                                switch($stageCode) {
                                                    case 'proposal_submission':
                                                    case 'proposal_approved':
                                                        $deadlineRoute = route('mahasiswa.proposal.index');
                                                        break;
                                                    case 'bimbingan_progress':
                                                        $deadlineRoute = route('mahasiswa.bimbingan.index');
                                                        break;
                                                    case 'story_conference':
                                                        $deadlineRoute = route('mahasiswa.story-conference.create');
                                                        break;
                                                    case 'production_upload':
                                                    case 'final_submission':
                                                        $deadlineRoute = route('mahasiswa.produksi.manage');
                                                        break;
                                                    case 'exam_registration':
                                                    case 'exam_completed':
                                                        $deadlineRoute = route('mahasiswa.ujian-ta.index');
                                                        break;
                                                    default:
                                                        $deadlineRoute = route('mahasiswa.proposal.index');
                                                }
                                            @endphp
                                            <a href="{{ $deadlineRoute }}" class="inline-block px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                                Lihat
                                            </a>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 py-4 text-center">Tidak ada deadline mendatang</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Cepat</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Bimbingan</span>
                                        <span class="text-lg font-bold text-yellow-600">{{ $totalBimbingan }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Bimbingan Disetujui</span>
                                        <span class="text-lg font-bold text-green-600">{{ $approvedBimbinganCount ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Bimbingan Menunggu</span>
                                        <span class="text-lg font-bold text-yellow-600">{{ $pendingBimbinganCount ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">File Terupload</span>
                                        <span class="text-lg font-bold text-yellow-500">{{ $fileTerupload }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        function toggleProgressDetail() {
            const detail = document.getElementById('progressDetail');
            detail.classList.toggle('hidden');
        }
    </script>
</body>
</html>
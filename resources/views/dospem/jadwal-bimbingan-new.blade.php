<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Bimbingan - Tamago ISI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <div class="mb-6 flex space-x-2 border-b border-gray-200">
                        <button onclick="switchTab('list')" class="tab-btn px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-medium" data-tab="list">
                            <i class="fas fa-list mr-2"></i>Daftar Permintaan
                        </button>
                    </div>

                    <!-- Tab: List View -->
                    <div id="list-tab" class="tab-content bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-blue-800">Daftar Permintaan Jadwal Bimbingan</h3>
                        </div>
                        
                        <!-- Filter buttons -->
                        <div class="mb-4 flex flex-wrap gap-2">
                            <button onclick="filterStatus('all')" class="filter-btn px-4 py-2 rounded-md text-sm bg-blue-100 text-blue-700 font-medium border border-blue-300" data-status="all">
                                Semua
                            </button>
                            <button onclick="filterStatus('menunggu')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:bg-yellow-50" data-status="menunggu">
                                <i class="fas fa-clock mr-1"></i>Menunggu
                            </button>
                            <button onclick="filterStatus('disetujui')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:bg-green-50" data-status="disetujui">
                                <i class="fas fa-check mr-1"></i>Disetujui
                            </button>
                            <button onclick="filterStatus('ditolak')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:bg-red-50" data-status="ditolak">
                                <i class="fas fa-times mr-1"></i>Ditolak
                            </button>
                        </div>

                        <!-- List of jadwal requests -->
                        <div id="jadwal-list" class="space-y-3">
                            @forelse($jadwals ?? [] as $jadwal)
                                <div class="border rounded-lg p-4 bg-white hover:bg-gray-50 transition flex justify-between items-start jadwal-item" data-status="{{ $jadwal->status ?? 'menunggu' }}">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="font-bold text-blue-600">{{ strtoupper(substr($jadwal->mahasiswa->nama ?? 'M', 0, 1)) }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ $jadwal->mahasiswa->nama ?? 'Mahasiswa' }}</h4>
                                                <p class="text-xs text-gray-500">{{ $jadwal->mahasiswa->nim ?? '-' }}</p>
                                            </div>
                                            <span class="text-xs px-3 py-1 rounded-full font-semibold
                                                @if(($jadwal->status ?? 'menunggu') === 'disetujui')
                                                    bg-green-100 text-green-800
                                                @elseif(($jadwal->status ?? 'menunggu') === 'ditolak')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-yellow-100 text-yellow-800
                                                @endif
                                            ">
                                                @if(($jadwal->status ?? 'menunggu') === 'disetujui')
                                                    <i class="fas fa-check mr-1"></i>Disetujui
                                                @elseif(($jadwal->status ?? 'menunggu') === 'ditolak')
                                                    <i class="fas fa-times mr-1"></i>Ditolak
                                                @else
                                                    <i class="fas fa-clock mr-1"></i>Menunggu
                                                @endif
                                            </span>
                                        </div>
                                        <div class="space-y-1 mt-3">
                                            <p class="text-sm text-gray-700">
                                                <i class="fas fa-calendar-alt w-4 text-blue-600"></i>
                                                {{ $jadwal->tanggal?->format('d M Y') ?? '-' }}
                                                <span class="text-gray-500 ml-2">Pukul {{ date('H:i', strtotime($jadwal->jam_mulai ?? '10:00')) }}</span>
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <i class="fas fa-map-marker-alt w-4 text-blue-600"></i>
                                                {{ $jadwal->tempat ?? 'Tempat tidak ditentukan' }}
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <i class="fas fa-book w-4 text-blue-600"></i>
                                                {{ $jadwal->topik ?? 'Bimbingan' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex gap-2">
                                        @if(($jadwal->status ?? 'menunggu') === 'menunggu')
                                            <button onclick="openJadwalModal({{ $jadwal->id ?? 0 }})" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Review
                                            </button>
                                        @else
                                            <button onclick="openJadwalModal({{ $jadwal->id ?? 0 }})" class="px-4 py-2 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg">Tidak ada jadwal bimbingan</p>
                                    <p class="text-gray-400 text-sm">Tunggu mahasiswa mengirimkan permintaan jadwal bimbingan</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- MODAL APPROVAL -->
    @include('dospem.modals.jadwal-approval-modal')

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-blue-600', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            document.getElementById(tabName + '-tab').classList.remove('hidden');
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('border-blue-600', 'text-blue-600');
            document.querySelector(`[data-tab="${tabName}"]`).classList.remove('border-transparent', 'text-gray-600');
        }

        function filterStatus(status) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.status === status) {
                    btn.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-300');
                    btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                } else {
                    btn.classList.remove('bg-blue-100', 'text-blue-700', 'border-blue-300');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                }
            });

            document.querySelectorAll('.jadwal-item').forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('[role="alert"]').forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>

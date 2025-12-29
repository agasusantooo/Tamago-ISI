<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator TEFA - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('koordinator_tefa.partials.sidebar-koordinator')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- Note: header-koordinator mungkin perlu dibuat juga jika isinya berbeda --}}
            @include('koordinator_ta.partials.header-koordinator')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Dashboard Koordinator TEFA Fair</h3>
                        </div>

                        <!-- Stats -->
                        <div class="col-span-12 grid grid-cols-3 gap-4 mb-4">
                            <div class="bg-white rounded-xl shadow p-5">
                                <p class="text-sm text-gray-500">Total Pendaftar TEFA</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalRegistrations ?? 0 }}</p>
                                <p class="text-xs text-green-500 mt-1">Total proyek yang terdaftar untuk TEFA</p>
                            </div>

                            <div class="bg-white rounded-xl shadow p-5">
                                <p class="text-sm text-gray-500">Disetujui</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $accepted ?? 0 }}</p>
                                <p class="text-xs text-blue-500 mt-1">{{ $totalRegistrations ? round(($accepted/$totalRegistrations)*100, 1) : 0 }}% dari total</p>
                            </div>

                            <div class="bg-white rounded-xl shadow p-5">
                                <p class="text-sm text-gray-500">Menunggu Review</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $pendingCount ?? 0 }}</p>
                                <p class="text-xs text-green-500 mt-1">Menunggu keputusan panitia</p>
                            </div>
                        </div>

                        <!-- Pending tasks and upcoming events -->
                        <div class="col-span-8">
                            <div class="bg-white rounded-xl shadow p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold">Tugas Menunggu Persetujuan</h4>
                                    <span class="text-xs text-gray-500">{{ collect($pending['tefa_registrations'] ?? [])->count() }} Pending</span>
                                </div>

                                <div class="space-y-3">
                                    @foreach($pending['tefa_registrations'] as $t)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                        <div>
                                            <div class="text-sm font-medium">Pendaftaran TEFA</div>
                                            <div class="text-xs text-gray-600">Mahasiswa: {{ optional($t->mahasiswa)->nama ?? 'N/A' }} ({{ optional($t->mahasiswa)->nim ?? 'N/A' }})</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('koordinator_tefa.monitoring') }}" class="px-3 py-1 bg-green-600 text-white rounded">✓</a>
                                            <a href="{{ route('koordinator_tefa.monitoring') }}" class="px-3 py-1 bg-red-600 text-white rounded">✕</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming events -->
                        <div class="col-span-4">
                            <div class="bg-white rounded-xl shadow p-4">
                                <h4 class="font-semibold mb-3">Jadwal Mendatang</h4>
                                <div class="space-y-3">
                                    @forelse($upcoming as $jadwal)
                                    <div class="p-3 rounded border-l-4 border-green-200 bg-green-50">
                                        <div class="text-sm font-semibold">{{ \Carbon\Carbon::parse($jadwal->start)->format('d M') }}</div>
                                        <div class="text-sm">{{ $jadwal->title }} • {{ \Carbon\Carbon::parse($jadwal->start)->format('H:i') }} • {{ $jadwal->type ?? 'Acara' }}</div>
                                        <div class="text-xs text-gray-600">{{ optional($jadwal)->description ?? '' }}</div>
                                    </div>
                                    @empty
                                    <div class="text-sm text-gray-500">Tidak ada jadwal mendatang.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Announcements -->
                        <div class="col-span-12 mt-4">
                            <div class="bg-white rounded-xl shadow p-4">
                                <h4 class="font-semibold mb-3">Pengumuman Global</h4>
                                <div class="space-y-3">
                                    @foreach($announcements as $a)
                                    <div class="p-4 rounded bg-yellow-50 border border-yellow-100">
                                        <div class="font-semibold">{{ $a['title'] }}</div>
                                        <div class="text-sm text-gray-700 mt-1">{{ $a['body'] }}</div>
                                        <div class="text-xs text-gray-500 mt-2">{{ $a['meta'] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
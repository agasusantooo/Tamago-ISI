<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 text-gray-800">
    @php
        // Dummy data untuk menghindari error di header
        $mahasiswaAktifCount = 150; 
        $tugasReview = 25; 
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('koordinator_ta.partials.sidebar-koordinator')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('koordinator_ta.partials.header-koordinator')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Dashboard Koordinator TA</h3>
                        <p>Selamat datang di dashboard Koordinator Tugas Akhir. Silakan gunakan menu di samping untuk mengelola jadwal, memonitor mahasiswa, dan mengatur mata kuliah.</p>
                    </div>

                    {{-- Sample student progress card (real-time) --}}
                    <div class="mt-6 bg-white rounded-xl shadow-sm p-6 max-w-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-md font-semibold text-gray-700">Progres Tugas Akhir</h4>
                                <p class="text-sm text-gray-500">Pratinjau progres mahasiswa</p>
                            </div>
                            @if(!empty($sampleStudentName))
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Mahasiswa</div>
                                    <div class="text-base font-medium text-gray-800">{{ $sampleStudentName }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center">
                            @php
                                $pct = isset($sampleProgress['percentage']) ? (int)$sampleProgress['percentage'] : 0;
                                // SVG circle values
                                $radius = 48;
                                $circumference = 2 * pi() * $radius;
                                $dash = ($pct / 100) * $circumference;
                            @endphp

                            <div class="flex items-center justify-center w-40 h-40">
                                <svg width="110" height="110" viewBox="0 0 110 110">
                                    <defs>
                                        <linearGradient id="g1" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#FBBF24" />
                                            <stop offset="100%" stop-color="#F59E0B" />
                                        </linearGradient>
                                    </defs>
                                    <g transform="translate(55,55)">
                                        <circle r="{{ $radius }}" fill="none" stroke="#EEF2F7" stroke-width="12" />
                                        <circle r="{{ $radius }}" fill="none" stroke="url(#g1)" stroke-width="12"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ $dash }} {{ max(0, $circumference - $dash) }}"
                                            transform="rotate(-90)"
                                        />
                                        <text x="0" y="6" text-anchor="middle" font-size="20" font-weight="700" fill="#92400E">{{ $pct }}%</text>
                                    </g>
                                </svg>
                            </div>

                            <div class="ml-6 flex-1">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                        <span class="text-sm text-gray-700">Proposal</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                                        <span class="text-sm text-gray-700">Bimbingan</span>
                                    </div>
                                </div>

                                @if(!empty($sampleProgress['details']))
                                    <div class="text-sm text-gray-600">
                                        @foreach($sampleProgress['details'] as $d)
                                            <div class="flex justify-between py-1">
                                                <div>{{ $d['name'] }}</div>
                                                <div class="font-medium">{{ intval($d['fraction'] * 100) }}%</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">Tidak ada data progres mahasiswa.</div>
                                @endif
                                {{-- Debug: raw progress payload --}}
                                <div class="mt-4 text-xs text-gray-400">Raw: <pre class="whitespace-pre-wrap">{{ isset($sampleProgress) ? json_encode($sampleProgress) : 'null' }}</pre></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
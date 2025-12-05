<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kegiatan - Tamago ISI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
</head>
<body class="bg-green-50 text-gray-800">
    @php
        $mahasiswaAktifCount = 150; 
        $tugasReview = 25; 
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('koordinator_ta.partials.sidebar-koordinator')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('koordinator_ta.partials.header-koordinator')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-xl font-bold text-green-800 mb-6">Jadwal Story Conference & TEFA Fair</h3>
                        
                        <div class="space-y-6">
                            @if(isset($jadwal) && count($jadwal) > 0)
                                @foreach($jadwal as $item)
                                    <div class="p-5 border-l-4 rounded-r-lg {{ $loop->first ? 'border-purple-500 bg-purple-50' : 'border-green-500 bg-green-50' }}">
                                        <h4 class="text-lg font-semibold {{ $loop->first ? 'text-purple-800' : 'text-green-800' }}">{{ $item['nama'] }}</h4>
                                        <p class="text-sm text-gray-600 mt-1 mb-2">{{ $item['deskripsi'] }}</p>
                                        <div class="text-sm font-medium text-gray-800">
                                            <i class="fas fa-calendar-alt fa-fw mr-2"></i>
                                            <span>Periode:</span>
                                            <span class="font-semibold">{{ $item['tanggal_mulai'] }} - {{ $item['tanggal_akhir'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-gray-500">Tidak ada jadwal yang tersedia saat ini.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

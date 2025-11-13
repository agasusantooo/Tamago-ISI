<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-blue-50 text-gray-800">
    @php
        $mahasiswaAktifCount = 15; 
        $tugasReview = 5; 
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    
                    <!-- Tombol Kembali -->
                    <div class="mb-4">
                        <a href="{{ route('dospem.mahasiswa-bimbingan') }}" class="text-sm text-blue-600 hover:underline">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Mahasiswa
                        </a>
                    </div>

                    <!-- Kartu Profil Mahasiswa -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($mahasiswa->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-blue-900">{{ $mahasiswa->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $mahasiswa->nim }} - {{ $mahasiswa->email }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-semibold text-gray-800">Judul Tugas Akhir:</h3>
                            <p class="text-gray-600">{{ $mahasiswa->judul_ta }}</p>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-semibold text-gray-800">Progress Keseluruhan:</h3>
                            <div class="flex items-center justify-center space-x-2 mt-1">
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-blue-600 h-4 rounded-full text-center text-white text-xs" style="width: {{ $mahasiswa->progress }}%">{{ $mahasiswa->progress }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Bimbingan -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Riwayat Bimbingan</h3>
                        <div class="space-y-4">
                            @forelse($mahasiswa->riwayat_bimbingan as $riwayat)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <p class="font-semibold text-gray-800">{{ $riwayat['catatan'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $riwayat['tanggal'] }}</p>
                            </div>
                            @empty
                            <p class="text-gray-500">Belum ada riwayat bimbingan.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>

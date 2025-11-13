<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Ujian TA - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-purple-50 text-gray-800">
    @php
        $mahasiswaAktifCount = 10; 
        $tugasReview = 5; 
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dosen_penguji.partials.sidebar-dosen_penguji')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dosen_penguji.partials.header-dosen_penguji')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4">Daftar Ujian TA untuk Dinilai</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-purple-50 border-b border-purple-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700">Judul TA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-purple-700">Tanggal Ujian</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-purple-700">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-purple-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-50">
                                    @php
                                    $ujian = [
                                        ['id' => 1, 'nama' => 'Budi Santoso', 'judul' => 'Sistem Rekomendasi Film', 'tanggal' => '2025-11-20', 'status' => 'Belum Dinilai'],
                                        ['id' => 2, 'nama' => 'Siti Lestari', 'judul' => 'Analisis Sentimen Media Sosial', 'tanggal' => '2025-11-21', 'status' => 'Belum Dinilai'],
                                        ['id' => 3, 'nama' => 'Ahmad Fauzi', 'judul' => 'Aplikasi Mobile untuk Petani', 'tanggal' => '2025-11-19', 'status' => 'Sudah Dinilai'],
                                    ];
                                    @endphp

                                    @foreach($ujian as $item)
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item['nama'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item['judul'] }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $item['tanggal'] }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($item['status'] == 'Belum Dinilai')
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Belum Dinilai</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Sudah Dinilai</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($item['status'] == 'Belum Dinilai')
                                                <button class="px-3 py-1 text-xs bg-purple-600 text-white rounded hover:bg-purple-700">
                                                    <i class="fas fa-edit mr-1"></i>Nilai
                                                </button>
                                            @else
                                                <button class="px-3 py-1 text-xs bg-gray-400 text-white rounded cursor-not-allowed" disabled>
                                                    <i class="fas fa-check mr-1"></i>Sudah Dinilai
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

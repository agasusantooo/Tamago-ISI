<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Mahasiswa - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Monitoring & Persetujuan Tahapan Mahasiswa</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-green-50 border-b border-green-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">NIM</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Judul TA</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Tahapan Saat Ini</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-green-50">
                                    @php
                                    $mahasiswa = [
                                        ['nim' => '19.11.2740', 'nama' => 'Budi Santoso', 'judul' => 'Sistem Rekomendasi Film', 'tahapan' => 'Proposal Bab 1-3', 'status' => 'Menunggu Persetujuan'],
                                        ['nim' => '19.11.2741', 'nama' => 'Siti Lestari', 'judul' => 'Analisis Sentimen Media Sosial', 'tahapan' => 'Pra-Produksi', 'status' => 'Disetujui'],
                                        ['nim' => '19.11.2742', 'nama' => 'Ahmad Fauzi', 'judul' => 'Aplikasi Mobile untuk Petani', 'tahapan' => 'Produksi', 'status' => 'Disetujui'],
                                        ['nim' => '19.11.2743', 'nama' => 'Dewi Anggraini', 'judul' => 'Game Edukasi Sejarah', 'tahapan' => 'Proposal Bab 1-3', 'status' => 'Revisi'],
                                    ];
                                    @endphp

                                    @foreach($mahasiswa as $mhs)
                                    <tr class="hover:bg-green-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $mhs['nim'] }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $mhs['nama'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $mhs['judul'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $mhs['tahapan'] }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($mhs['status'] == 'Menunggu Persetujuan')
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Menunggu</span>
                                            @elseif($mhs['status'] == 'Disetujui')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Disetujui</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Revisi</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($mhs['status'] == 'Menunggu Persetujuan')
                                                <button class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Setujui</button>
                                            @else
                                                <button class="px-3 py-1 text-xs bg-gray-400 text-white rounded cursor-not-allowed" disabled>Setujui</button>
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

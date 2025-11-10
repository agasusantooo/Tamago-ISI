<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Bimbingan - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-blue-50 text-gray-800">
    @php
        $mahasiswaAktifCount = 15; // Dummy data
        $tugasReview = 5; // Dummy data
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-blue-800">Daftar Mahasiswa</h3>
                            <div class="flex items-center space-x-2">
                                <input type="text" placeholder="Cari mahasiswa..." class="px-4 py-2 border rounded-lg text-sm">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50 border-b border-blue-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Judul TA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Progress</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Bimbingan Terakhir</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-50">
                                    @php
                                    $mahasiswaBimbingan = [
                                        (object)['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'judul_ta' => 'Sistem Rekomendasi Film', 'progress' => 75, 'bimbingan_terakhir' => '2025-11-01'],
                                        (object)['name' => 'Siti Lestari', 'email' => 'siti@example.com', 'judul_ta' => 'Analisis Sentimen Media Sosial', 'progress' => 60, 'bimbingan_terakhir' => '2025-10-28'],
                                        (object)['name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com', 'judul_ta' => 'Aplikasi Mobile untuk Petani', 'progress' => 90, 'bimbingan_terakhir' => '2025-11-05'],
                                    ];
                                    @endphp
                                    @forelse($mahasiswaBimbingan as $mhs)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="font-bold text-blue-600">{{ strtoupper(substr($mhs->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-blue-900">{{ $mhs->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $mhs->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-gray-700">{{ $mhs->judul_ta ?? 'Belum ada judul' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $mhs->progress }}%"></div>
                                                </div>
                                                <span class="text-sm font-bold text-blue-600">{{ $mhs->progress }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $mhs->bimbingan_terakhir }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada mahasiswa bimbingan</td>
                                    </tr>
                                    @endforelse
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

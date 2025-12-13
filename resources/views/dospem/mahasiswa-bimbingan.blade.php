<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Bimbingan - Tamago ISI</title>
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
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <!-- Header Section -->
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-blue-800 mb-1">Daftar Mahasiswa</h1>
                            <div class="text-sm text-gray-600">Mahasiswa Aktif: <span id="dospemMahasiswaAktif" class="font-semibold text-blue-700">{{ $mahasiswaAktifCount ?? '-' }}</span> | Tugas Review: <span id="dospemTugasReview" class="font-semibold text-blue-700">{{ $tugasReview ?? '-' }}</span></div>
                        </div>

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
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">NIM</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Judul TA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Progress</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Bimbingan Terakhir</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="mahasiswaBimbinganTable" class="divide-y divide-blue-50">
                                    @if(isset($mahasiswaBimbingan) && count($mahasiswaBimbingan))
                                        @forelse($mahasiswaBimbingan as $mhs)
                                            <tr class="hover:bg-blue-50 transition">
                                                <td class="px-4 py-3 text-sm text-gray-700">{{ $mhs->nim }}</td>
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
                                                    <a href="{{ route('dospem.mahasiswa-bimbingan.show', ['id' => $mhs->nim ?? $mhs->id]) }}" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data mahasiswa bimbingan.</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-gray-500">Data mahasiswa bimbingan tidak tersedia.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Real-time polling for mahasiswa bimbingan table
        async function fetchMahasiswaBimbinganData() {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch("{{ route('dospem.mahasiswa-bimbingan.data') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                if (!res.ok) return;
                const data = await res.json();

                // Update header counts
                if (document.getElementById('dospemMahasiswaAktif')) {
                    document.getElementById('dospemMahasiswaAktif').textContent = data.data.mahasiswaAktifCount ?? 0;
                }
                if (document.getElementById('dospemTugasReview')) {
                    document.getElementById('dospemTugasReview').textContent = data.data.tugasReview ?? 0;
                }

                // Update table body
                const tableBody = document.getElementById('mahasiswaBimbinganTable');
                if (tableBody && data.data.mahasiswaBimbingan) {
                    tableBody.innerHTML = '';
                    if (data.data.mahasiswaBimbingan.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data mahasiswa bimbingan.</td></tr>';
                    } else {
                        data.data.mahasiswaBimbingan.forEach(function(mhs) {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-blue-50 transition';
                            row.innerHTML = `
                                <td class="px-4 py-3 text-sm text-gray-700">${mhs.nim}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="font-bold text-blue-600">${mhs.name.charAt(0).toUpperCase()}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-blue-900">${mhs.name}</p>
                                            <p class="text-xs text-gray-500">${mhs.email}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-700">${mhs.judul_ta ?? 'Belum ada judul'}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${mhs.progress ?? 0}%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-blue-600">${mhs.progress ?? 0}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">${mhs.bimbingan_terakhir}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ url('dospem/mahasiswa-bimbingan/') }}/${mhs.nim}" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    }
                }
            } catch (e) {
                console.error('Failed to fetch mahasiswa bimbingan data', e);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchMahasiswaBimbinganData();
            // Poll every 15 seconds
            setInterval(fetchMahasiswaBimbinganData, 15000);
        });
    </script>
</body>
</html>

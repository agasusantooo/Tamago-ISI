<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Penilaian Ujian TA - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-purple-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        @include('dosen_penguji.partials.sidebar-dosen_penguji')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('dosen_penguji.partials.header-dosen_penguji')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-purple-900">Penilaian Ujian TA</h1>
                        <p class="text-gray-600 mt-2">Berikan nilai untuk ujian TA mahasiswa</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-gray-600 font-semibold">NIM</th>
                                        <th class="text-left py-3 px-4 text-gray-600 font-semibold">Nama Mahasiswa</th>
                                        <th class="text-left py-3 px-4 text-gray-600 font-semibold">Judul TA</th>
                                        <th class="text-left py-3 px-4 text-gray-600 font-semibold">Tanggal Ujian</th>
                                        <th class="text-left py-3 px-4 text-gray-600 font-semibold">Status</th>
                                        <th class="text-center py-3 px-4 text-gray-600 font-semibold">Nilai</th>
                                        <th class="text-center py-3 px-4 text-gray-600 font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="ujianTableBody">
                                    @forelse($ujianList as $ujian)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium">{{ $ujian['nim'] }}</td>
                                        <td class="py-3 px-4">{{ $ujian['nama'] }}</td>
                                        <td class="py-3 px-4 text-sm">{{ $ujian['judul'] }}</td>
                                        <td class="py-3 px-4 text-sm">{{ $ujian['tanggal'] }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                @if($ujian['status'] == 'Sudah Dinilai') bg-green-100 text-green-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $ujian['status'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center font-semibold">
                                            {{ $ujian['nilai'] ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($ujian['status'] == 'Belum Dinilai')
                                                <button class="px-3 py-1 text-xs bg-purple-600 text-white rounded hover:bg-purple-700 transition" 
                                                        onclick="openNilaiModal({{ json_encode($ujian) }})">
                                                    <i class="fas fa-edit mr-1"></i>Nilai
                                                </button>
                                            @else
                                                <button class="px-3 py-1 text-xs bg-gray-400 text-white rounded cursor-not-allowed" disabled>
                                                    <i class="fas fa-check mr-1"></i>Dinilai
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="border-b border-gray-100">
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                                            Tidak ada ujian untuk dinilai
                                        </td>
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

    <!-- Nilai Modal -->
    <div id="nilaiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96">
            <h2 class="text-2xl font-bold text-purple-900 mb-4">Berikan Nilai</h2>
            
            <form id="nilaiForm" onsubmit="submitNilai(event)">
                @csrf
                <input type="hidden" id="ujianId" name="ujian_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Mahasiswa</label>
                    <p id="mahasiswaNama" class="text-gray-900 font-semibold p-2 bg-gray-50 rounded"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                    <p id="mahasiswaNim" class="text-gray-900 p-2 bg-gray-50 rounded"></p>
                </div>

                <div class="mb-4">
                    <label for="nilaiInput" class="block text-sm font-medium text-gray-700 mb-2">Nilai (0-100)</label>
                    <input type="number" id="nilaiInput" name="nilai" min="0" max="100" step="0.5" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeNilaiModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNilaiModal(ujian) {
            document.getElementById('ujianId').value = ujian.id;
            document.getElementById('mahasiswaNama').textContent = ujian.nama;
            document.getElementById('mahasiswaNim').textContent = ujian.nim;
            document.getElementById('nilaiInput').value = ujian.nilai || '';
            document.getElementById('nilaiModal').classList.remove('hidden');
        }

        function closeNilaiModal() {
            document.getElementById('nilaiModal').classList.add('hidden');
            document.getElementById('nilaiForm').reset();
        }

        function submitNilai(e) {
            e.preventDefault();
            
            const ujianId = document.getElementById('ujianId').value;
            const nilai = document.getElementById('nilaiInput').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('{{ route("dosen_penguji.penilaian.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    ujian_id: ujianId,
                    nilai: nilai,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Nilai berhasil disimpan!');
                    closeNilaiModal();
                    refreshPenilaianData();
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan: ' + err.message);
            });
        }

        function refreshPenilaianData() {
            fetch('{{ route("dosen_penguji.penilaian.data") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    updatePenilaianTable(data.data.ujianList);
                }
            })
            .catch(err => console.error('Error refreshing data:', err));
        }

        function updatePenilaianTable(ujianList) {
            const tbody = document.getElementById('ujianTableBody');
            if (!tbody) return;

            if (ujianList.length === 0) {
                tbody.innerHTML = `
                    <tr class="border-b border-gray-100">
                        <td colspan="7" class="py-6 text-center text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                            Tidak ada ujian untuk dinilai
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = ujianList.map(ujian => `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">${ujian.nim}</td>
                    <td class="py-3 px-4">${ujian.nama}</td>
                    <td class="py-3 px-4 text-sm">${ujian.judul}</td>
                    <td class="py-3 px-4 text-sm">${ujian.tanggal}</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            ${ujian.status == 'Sudah Dinilai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${ujian.status}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center font-semibold">${ujian.nilai || '-'}</td>
                    <td class="py-3 px-4 text-center">
                        ${ujian.status == 'Belum Dinilai' 
                            ? `<button class="px-3 py-1 text-xs bg-purple-600 text-white rounded hover:bg-purple-700 transition" 
                                onclick='openNilaiModal(${JSON.stringify(ujian).replace(/'/g, "&#39;")})'>
                                <i class="fas fa-edit mr-1"></i>Nilai
                            </button>`
                            : `<button class="px-3 py-1 text-xs bg-gray-400 text-white rounded cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-1"></i>Dinilai
                            </button>`
                        }
                    </td>
                </tr>
            `).join('');
        }

        // Auto-refresh every 15 seconds
        setInterval(refreshPenilaianData, 15000);
    </script>
</body>
</html>
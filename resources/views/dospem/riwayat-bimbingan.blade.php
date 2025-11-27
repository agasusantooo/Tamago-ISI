<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Bimbingan - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-blue-800 mb-1">Riwayat Bimbingan</h1>
                            <p class="text-sm text-gray-600">Catatan semua sesi bimbingan yang telah dilakukan</p>
                        </div>

                        <!-- Filter Section -->
                        <div class="mb-6 flex flex-wrap gap-3">
                            <input 
                                type="text" 
                                id="searchMahasiswa" 
                                placeholder="Cari nama mahasiswa..." 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <select 
                                id="filterStatus" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Semua Status</option>
                                <option value="completed">Selesai</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50 border-b border-blue-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Topik Bimbingan</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Tanggal</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-50">
                                    @forelse($bimbingan as $item)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-blue-900">{{ $item->mahasiswa_name }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-gray-700">{{ $item->topik }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">
                                            {{ $item->tanggal ?? $item->created_at?->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($item->status === 'completed' || $item->status === 'disetujui')
                                                <span class="px-3 py-1 inline-block rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>{{ ucfirst($item->status) }}
                                                </span>
                                            @elseif($item->status === 'pending')
                                                <span class="px-3 py-1 inline-block rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-block rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button 
                                                onclick="viewDetail({{ $item->id }}, '{{ $item->mahasiswa_name }}', '{{ $item->topik }}')"
                                                class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                            <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                            Tidak ada riwayat bimbingan.
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

    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-white">Detail Bimbingan</h2>
                    <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 text-2xl">×</button>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">MAHASISWA</label>
                        <p class="text-sm font-semibold text-gray-800" id="detail-mahasiswa">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">TOPIK</label>
                        <p class="text-sm font-semibold text-gray-800" id="detail-topik">-</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Catatan Bimbingan</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap" id="detail-catatan">-</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Catatan dari Mahasiswa</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap" id="detail-catatan-mahasiswa">-</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end">
                <button 
                    onclick="closeDetailModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function viewDetail(id, mahasiswa, topik) {
            document.getElementById('detail-mahasiswa').textContent = mahasiswa;
            document.getElementById('detail-topik').textContent = topik;
            document.getElementById('detail-catatan').textContent = 'Bimbingan berjalan dengan baik...'; // TODO: Fetch from DB
            document.getElementById('detail-catatan-mahasiswa').textContent = 'Sudah mengerti materi yang diberikan'; // TODO: Fetch from DB
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Search functionality
        document.getElementById('searchMahasiswa').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const mahasiswa = row.querySelector('td:first-child').textContent.toLowerCase();
                row.style.display = mahasiswa.includes(query) ? '' : 'none';
            });
        });

        // Filter functionality
        document.getElementById('filterStatus').addEventListener('change', function(e) {
            const status = e.target.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (status === '') {
                    row.style.display = '';
                } else {
                    const rowStatus = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    row.style.display = rowStatus.includes(status) ? '' : 'none';
                }
            });
        });

        // Close modal on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('detailModal').classList.contains('hidden')) {
                closeDetailModal();
            }
        });
    </script>
</body>
</html>

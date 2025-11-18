<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Tugas - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @php
        $mahasiswaAktifCount = 15; 
        $tugasReview = 5; 
        $tugas = [
            ['id' => 1, 'mahasiswa' => 'Budi Santoso', 'jenis' => 'Proposal Bab 1', 'tanggal' => '2025-11-12', 'file' => '#'],
            ['id' => 2, 'mahasiswa' => 'Siti Lestari', 'jenis' => 'Storyboard Pra-Produksi', 'tanggal' => '2025-11-11', 'file' => '#'],
            ['id' => 3, 'mahasiswa' => 'Ahmad Fauzi', 'jenis' => 'Revisi Naskah Karya', 'tanggal' => '2025-11-10', 'file' => '#'],
            ['id' => 4, 'mahasiswa' => 'Budi Santoso', 'jenis' => 'Animatic Awal', 'tanggal' => '2025-11-09', 'file' => '#'],
        ];
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6" x-data="{ showModal: false, selectedTask: null }">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Tugas Mahasiswa untuk Direview</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50 border-b border-blue-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Jenis Tugas</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Tanggal Submit</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-50">
                                    @forelse($tugas as $item)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="px-4 py-3"><p class="font-medium text-blue-900">{{ $item['mahasiswa'] }}</p></td>
                                        <td class="px-4 py-3"><p class="text-sm text-gray-700">{{ $item['jenis'] }}</p></td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $item['tanggal'] }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ $item['file'] }}" download class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 mr-2">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </a>
                                            <button @click="selectedTask = {{ json_encode($item) }}; showModal = true" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                                <i class="fas fa-pen-to-square mr-1"></i>Review
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada tugas yang perlu direview.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div x-show="showModal" style="display: none;" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                        <div @click="showModal = false" x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-8 my-20 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Review Tugas: <span x-text="selectedTask ? selectedTask.jenis : ''"></span></h3>
                            <p class="text-sm text-gray-600">Mahasiswa: <span x-text="selectedTask ? selectedTask.mahasiswa : ''"></span></p>
                            
                            <div class="mt-4">
                                <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback atau Komentar</label>
                                <textarea id="feedback" name="feedback" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Tuliskan feedback Anda di sini..."></textarea>
                            </div>

                            <div class="mt-6 flex justify-between items-center">
                                <a :href="selectedTask ? selectedTask.file : '#'" download class="px-4 py-2 text-sm bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200">
                                    <i class="fas fa-download mr-1"></i> Download File
                                </a>
                                <div class="flex gap-3">
                                    <button @click="showModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Tutup</button>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Revisi Ditolak</button>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Setujui</button>
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
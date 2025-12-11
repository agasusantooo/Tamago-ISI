<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Mata Kuliah - Tamago ISI</title>
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
                <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Daftar Mata Kuliah -->
                    <div class="md:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Daftar Mata Kuliah Terkait TA</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-green-50 border-b border-green-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Kode MK</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Nama Mata Kuliah</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">SKS</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-green-50">
                                        @php
                                        $matakuliah = [
                                            ['kode' => 'TA-001', 'nama' => 'Pra-Produksi', 'sks' => 3],
                                            ['kode' => 'TA-002', 'nama' => 'Produksi', 'sks' => 6],
                                            ['kode' => 'TA-003', 'nama' => 'Pasca-Produksi', 'sks' => 3],
                                        ];
                                        @endphp
                                        @foreach($matakuliah as $mk)
                                        <tr class="hover:bg-green-50 transition">
                                            <td class="px-4 py-3 font-mono text-sm text-gray-600">{{ $mk['kode'] }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $mk['nama'] }}</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $mk['sks'] }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <button class="px-2 py-1 text-xs text-blue-800 rounded hover:bg-blue-100"><i class="fas fa-edit"></i></button>
                                                <button class="px-2 py-1 text-xs text-red-800 rounded hover:bg-red-100"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah/Edit Mata Kuliah -->
                    <div class="md:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Tambah Mata Kuliah Baru</h3>
                            <form action="#" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="kode_mk" class="block text-sm font-medium text-gray-700">Kode MK</label>
                                    <input type="text" id="kode_mk" name="kode_mk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="nama_mk" class="block text-sm font-medium text-gray-700">Nama Mata Kuliah</label>
                                    <input type="text" id="nama_mk" name="nama_mk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="sks" class="block text-sm font-medium text-gray-700">SKS</label>
                                    <input type="number" id="sks" name="sks" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Naskah & Karya - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @section('page-title', 'Naskah & Karya')
    <div class="flex h-screen overflow-hidden">
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('mahasiswa.partials.header-mahasiswa')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">

                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <p class="text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-1"></i>
                                <div>
                                    <p class="font-semibold text-red-800">Terdapat kesalahan:</p>
                                    <ul class="list-disc list-inside text-red-700 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Naskah & Karya Final</h2>
                        <p class="text-sm text-gray-600">Upload naskah publikasi dan file karya final yang telah disetujui untuk finalisasi tugas akhir</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- main column -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- status box (placeholder) -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-semibold text-gray-800 mb-4">Status Finalisasi</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="p-4 border rounded text-center">
                                        <div class="text-yellow-500 mb-2"><i class="fas fa-hourglass-half fa-2x"></i></div>
                                        <div class="font-semibold text-sm">Menunggu Verifikasi</div>
                                    </div>
                                    <div class="p-4 border rounded text-center">
                                        <div class="text-green-500 mb-2"><i class="fas fa-check-circle fa-2x"></i></div>
                                        <div class="font-semibold text-sm">Telah Diverifikasi</div>
                                    </div>
                                    <div class="p-4 border rounded text-center">
                                        <div class="text-red-500 mb-2"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                                        <div class="font-semibold text-sm">Ada Kekurangan</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Naskah Publikasi -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-2">Upload Naskah Publikasi</h3>
                                <p class="text-xs text-gray-500 mb-4">Upload file naskah publikasi dalam format PDF atau DOC. Jika sudah dipublikasikan, cantumkan link jurnal di bawah.</p>

                                <form method="POST" action="{{ route('mahasiswa.naskah-karya.upload') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('fileNaskah').click()">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-sm text-gray-600 mb-1">Drop file di sini atau klik untuk upload</p>
                                        <button type="button" onclick="document.getElementById('fileNaskah').click()" class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Pilih File</button>
                                        <p class="text-xs text-gray-500 mt-2">Maksimum 50MB. PDF, DOC, DOCX, ZIP</p>
                                        <input type="file" id="fileNaskah" name="file_naskah" accept=".pdf,.doc,.docx,.zip" class="hidden" onchange="updateFileName(this, 'naskahFileName')">
                                        <p id="naskahFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700">Link Jurnal (opsional)</label>
                                        <input type="url" name="link_jurnal" value="{{ old('link_jurnal', optional($projek)->link_jurnal) }}" placeholder="https://journal.example.com/article/123" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 text-sm" />
                                    </div>

                                    @if(optional($projek)->file_naskah_publikasi)
                                        <div class="mt-3 bg-green-50 border border-green-200 rounded p-3">
                                            <p class="text-sm text-green-800 mb-1"><i class="fas fa-check-circle mr-1"></i> Naskah sudah diunggah</p>
                                            <a href="{{ route('mahasiswa.naskah-karya.download', [$projek->id_proyek_akhir ?? $projek->id, 'naskah']) }}" class="text-sm text-blue-600 hover:underline"><i class="fas fa-download mr-1"></i> Download Naskah</a>
                                        </div>
                                    @endif

                                    <div class="flex justify-end mt-4">
                                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Upload Naskah</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Upload Karya Final (use ProduksiController route) -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-2">Upload Karya Final</h3>
                                <p class="text-xs text-gray-500 mb-4">Upload file karya final (Video/PDF/ZIP). Karya akhir hanya bisa diunggah jika pra produksi disetujui.</p>

                                @if(optional($produksi)->status_pra_produksi === 'disetujui')
                                    <form method="POST" action="{{ route('mahasiswa.produksi.produksi-akhir') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('fileKarya').click()">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                            <p class="text-sm text-gray-600 mb-1">Drop file di sini atau klik untuk upload</p>
                                            <p class="text-xs text-gray-500 mt-2">Maksimum 500MB - MP4/MOV/PDF/ZIP</p>
                                            <input type="file" id="fileKarya" name="file_produksi_akhir" accept=".mp4,.mov,.avi,.mkv,.pdf,.zip" class="hidden" onchange="updateFileName(this, 'karyaFileName')">
                                            <button type="button" onclick="document.getElementById('fileKarya').click()" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Pilih File</button>
                                            <p id="karyaFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                        </div>

                                        @if(optional($produksi)->file_produksi_akhir)
                                            <div class="mt-3 bg-green-50 border border-green-200 rounded p-3">
                                                <p class="text-xs text-green-800 mb-1"><i class="fas fa-check-circle mr-1"></i> File sudah diunggah</p>
                                                <a href="{{ route('mahasiswa.produksi.download', [$produksi->id, 'akhir']) }}" class="text-sm text-blue-600 hover:underline"><i class="fas fa-download mr-1"></i> Download File</a>
                                            </div>
                                        @endif

                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Upload Karya</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-lock text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-sm text-gray-600">Pra produksi harus disetujui terlebih dahulu</p>
                                    </div>
                                @endif
                            </div>

                            <!-- File Tambahan -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-2">File Tambahan</h3>
                                <p class="text-xs text-gray-500 mb-4">Upload artbook, poster, teaser, atau infografis (opsional)</p>

                                @if(optional($produksi)->status_pra_produksi === 'disetujui')
                                    <form method="POST" action="{{ route('mahasiswa.produksi.luaran-tambahan') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Artbook</label>
                                                <input type="file" name="file_luaran_tambahan" accept=".pdf,.jpg,.jpeg,.png,.zip" class="mt-2" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Poster</label>
                                                <input type="file" name="file_luaran_tambahan" accept=".pdf,.jpg,.jpeg,.png,.zip" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700">Upload</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="text-center py-6 text-sm text-gray-500">Luaran tambahan tersedia setelah pra produksi disetujui</div>
                                @endif
                            </div>
                        </div>

                        <!-- right column -->
                        <div>
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-4">Alur Proses Finalisasi</h3>
                                <div class="space-y-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-check text-green-600"></i></div>
                                        <div>
                                            <p class="font-semibold">Pembimbingan</p>
                                            <p class="text-xs text-gray-500">Selesai</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-check text-green-600"></i></div>
                                        <div>
                                            <p class="font-semibold">Pengesahan</p>
                                            <p class="text-xs text-gray-500">Selesai</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-cloud-upload-alt text-yellow-600"></i></div>
                                        <div>
                                            <p class="font-semibold">Upload Final</p>
                                            <p class="text-xs text-gray-500">Sedang Berlangsung</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-hourglass text-gray-500"></i></div>
                                        <div>
                                            <p class="font-semibold">Selesai</p>
                                            <p class="text-xs text-gray-500">Menunggu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-between">
                                    <button class="px-4 py-2 bg-white border rounded">Simpan Draft</button>
                                    <button class="px-4 py-2 bg-yellow-400 text-black rounded">Ajukan Verifikasi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function updateFileName(input, displayId) {
            const file = input.files[0];
            const display = document.getElementById(displayId);
            if (file) {
                display.textContent = `✓ ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            }
        }
    </script>
</body>
</html>

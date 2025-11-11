<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bimbingan - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('mahasiswa.partials.header-mahasiswa')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <p class="text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <p class="text-red-700">{{ session('error') }}</p>
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

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content (2/3) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Riwayat Bimbingan -->
                            <div class="bg-white rounded-lg shadow-sm">
                                <div class="p-6 border-b">
                                    <h2 class="text-xl font-bold text-gray-800">Riwayat Bimbingan</h2>
                                    <p class="text-sm text-gray-600 mt-1">Daftar semua sesi bimbingan yang telah dilakukan</p>
                                </div>

                                <div class="divide-y">
                                    @forelse($bimbinganList as $bimbingan)
                                        <div class="p-6 hover:bg-gray-50 transition">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <h3 class="font-bold text-lg text-gray-800">{{ $bimbingan->topik }}</h3>
                                                        <span class="px-3 py-1 {{ $bimbingan->statusColor }} text-xs font-semibold rounded-full">
                                                            {{ $bimbingan->statusBadge['text'] }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                        <span>
                                                            <i class="fas fa-calendar mr-2"></i>
                                                            {{ $bimbingan->tanggal->format('d M Y') }}
                                                        </span>
                                                        @if($bimbingan->waktu_mulai)
                                                            <span>
                                                                <i class="fas fa-clock mr-2"></i>
                                                                {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }} WIB
                                                            </span>
                                                        @endif
                                                        @if($bimbingan->ruang)
                                                            <span>
                                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                                {{ $bimbingan->ruang }}
                                                            </span>
                                                        @endif>
                                                    </div>
                                                </div>
                                                <button onclick="toggleDetail({{ $bimbingan->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                    Lihat Detail
                                                </button>
                                            </div>

                                            <!-- Detail Section (Hidden by default) -->
                                            <div id="detail-{{ $bimbingan->id }}" class="hidden mt-4 pt-4 border-t">
                                                <div class="space-y-3">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-700 mb-1">Catatan Mahasiswa:</p>
                                                        <p class="text-sm text-gray-600">{{ $bimbingan->catatan_mahasiswa }}</p>
                                                    </div>
                                                    
                                                    @if($bimbingan->catatan_dosen)
                                                        <div class="bg-blue-50 p-4 rounded-lg">
                                                            <p class="text-sm font-semibold text-blue-900 mb-1">
                                                                <i class="fas fa-user-tie mr-2"></i>Feedback Dosen:
                                                            </p>
                                                            <p class="text-sm text-blue-800">{{ $bimbingan->catatan_dosen }}</p>
                                                        </div>
                                                    @endif

                                                    @if($bimbingan->file_pendukung)
                                                        <div>
                                                            <a href="{{ route('mahasiswa.bimbingan.download', $bimbingan->id) }}" 
                                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                                <i class="fas fa-download mr-2"></i>
                                                                Download File Pendukung
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-12 text-center">
                                            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500">Belum ada riwayat bimbingan</p>
                                            <p class="text-sm text-gray-400 mt-2">Ajukan bimbingan baru untuk memulai</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar (1/3) -->
                        <div class="space-y-6">
                            <!-- Jadwal Terjadwal -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                    Jadwal Bimbingan
                                </h3>
                                
                                <div class="space-y-3">
                                    @forelse($jadwalTerjadwal as $jadwal)
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                            <div class="flex items-start justify-between mb-2">
                                                <p class="font-semibold text-sm text-gray-800">{{ $jadwal->topik }}</p>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                                    {{ $jadwal->statusBadge['text'] }}
                                                </span>
                                            </div>
                                            <div class="space-y-1 text-xs text-gray-600">
                                                <p>
                                                    <i class="fas fa-calendar w-4"></i>
                                                    {{ $jadwal->tanggal->format('d M Y') }}
                                                </p>
                                                @if($jadwal->waktu_mulai)
                                                    <p>
                                                        <i class="fas fa-clock w-4"></i>
                                                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} WIB
                                                    </p>
                                                @endif
                                                @if($jadwal->ruang)
                                                    <p>
                                                        <i class="fas fa-map-marker-alt w-4"></i>
                                                        {{ $jadwal->ruang }}
                                                    </p>
                                                @endif
                                                @if($jadwal->dosen)
                                                    <p>
                                                        <i class="fas fa-user-tie w-4"></i>
                                                        {{ $jadwal->dosen->nama }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6">
                                            <i class="fas fa-calendar-times text-3xl text-gray-300 mb-2"></i>
                                            <p class="text-sm text-gray-500">Tidak ada jadwal bimbingan</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Form Ajukan Bimbingan Baru -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                    Ajukan Bimbingan Baru
                                </h3>

                                <form method="POST" action="{{ route('mahasiswa.bimbingan.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Topik Bimbingan -->
                                    <div class="mb-4">
                                        <label for="topik" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Topik Bimbingan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="topik" name="topik" 
                                            value="{{ old('topik') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Contoh: Proposal BAB 1"
                                            required>
                                    </div>

                                    <!-- Catatan -->
                                    <div class="mb-4">
                                        <label for="catatan_mahasiswa" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Catatan atau Pertanyaan untuk Dosen <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="catatan_mahasiswa" name="catatan_mahasiswa" rows="4"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Jelaskan hal yang ingin dibimbingkan..."
                                            required>{{ old('catatan_mahasiswa') }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">Minimum 20 karakter</p>
                                    </div>

                                    <!-- File Pendukung -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            File Pendukung (Optional)
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition cursor-pointer" 
                                            onclick="document.getElementById('filePendukung').click()">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-xs text-gray-600 mb-1">Klik atau drag & drop file</p>
                                            <p class="text-xs text-gray-500">PDF, DOC, JPG, PNG (Max 5 MB)</p>
                                            <input type="file" id="filePendukung" name="file_pendukung" 
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                                                class="hidden" 
                                                onchange="updateFileName(this, 'fileNameDisplay')">
                                            <p id="fileNameDisplay" class="text-xs text-blue-600 font-medium mt-2"></p>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" 
                                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Bimbingan
                                    </button>
                                </form>
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

        function toggleDetail(id) {
            const detail = document.getElementById('detail-' + id);
            if (detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
            } else {
                detail.classList.add('hidden');
            }
        }

        // Drag & Drop
        const dropZone = document.getElementById('filePendukung').parentElement;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            document.getElementById('filePendukung').files = files;
            updateFileName(document.getElementById('filePendukung'), 'fileNameDisplay');
        }, false);
    </script>
</body>
</html>
@extends('mahasiswa.layouts.app')

@section('title', 'Pengajuan Proposal - Tamago ISI')

@section('content')
    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
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

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mt-8 justify-center">
        <!-- Form -->
        <div class="lg:col-span-2 flex justify-center">
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 w-full max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Form Pengajuan Proposal</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Lengkapi semua informasi yang diperlukan untuk mengajukan proposal tugas akhir.
                </p>

                <form id="proposalForm" method="POST" action="{{ route('mahasiswa.proposal.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Tugas Akhir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" 
                            value="{{ old('judul') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent"
                            placeholder="Masukkan Judul Tugas Akhir" required>
                    </div>
                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi Singkat/Abstrak <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent"
                            placeholder="Tuliskan Deskripsi Singkat atau Abstrak Proposal Anda" required>{{ old('deskripsi') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 100 karakter</p>
                    </div>
                    <!-- Dosen Pembimbing -->
                    <div class="mb-6">
                        <label for="dosen" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pilih Dosen Pembimbing
                        </label>
                        <select id="dosen" name="dosen_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }} - {{ $dosen->gelar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- File Proposal -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            File Proposal (PDF) <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-500 transition cursor-pointer"
                            onclick="document.getElementById('fileProposal').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                            <p class="text-xs text-gray-500">PDF, maksimal 10 MB</p>
                            <input type="file" id="fileProposal" name="file_proposal" accept=".pdf" class="hidden">
                            <p id="proposalFileName" class="text-sm text-yellow-700 font-medium mt-2"></p>
                        </div>
                    </div>
                    <!-- File Pitch Deck -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            File Pitch Deck (PDF/PPT)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-500 transition cursor-pointer"
                            onclick="document.getElementById('filePitchDeck').click()">
                            <i class="fas fa-file-powerpoint text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                            <p class="text-xs text-gray-500">PDF/PPT, maksimal 15 MB</p>
                            <input type="file" id="filePitchDeck" name="file_pitch_deck" accept=".pdf,.ppt,.pptx" class="hidden">
                            <p id="pitchDeckFileName" class="text-sm text-yellow-700 font-medium mt-2"></p>
                        </div>
                    </div>
                    <!-- Tombol -->
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-800 transition">
                            <i class="fas fa-paper-plane mr-2"></i>Ajukan Proposal
                        </button>
                        <button type="button" onclick="saveDraft()"
                            class="px-6 py-3 border-2 border-yellow-700 text-yellow-700 rounded-lg font-semibold hover:bg-yellow-50 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Draft
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Status Pengajuan -->
        <div>
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Status Pengajuan Proposal</h3>
                @if(isset($proposal) && $proposal)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Status:</p>
                            @php $badge = $proposal->statusBadge; @endphp
                            <span class="{{ $badge['class'] }} px-3 py-1 text-sm font-semibold rounded-full">
                                {{ $badge['text'] }}
                            </span>
                            @if($proposal->feedback)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">Feedback Reviewer:</p>
                                    <div class="bg-gray-50 rounded-lg p-3 mt-2 border-l-4 border-blue-500">
                                        <p class="text-gray-800 text-sm">{{ $proposal->feedback }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-600">Belum ada pengajuan proposal.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        function saveDraft() {
            const form = document.getElementById('proposalForm');
            const formData = new FormData(form);
            fetch("{{ route('mahasiswa.proposal.draft') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => alert(data.success ? data.message : 'Gagal menyimpan draft'))
            .catch(() => alert('Terjadi kesalahan saat menyimpan draft'));
        }
    </script>
@endsection

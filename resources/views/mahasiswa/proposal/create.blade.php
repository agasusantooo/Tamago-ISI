@php
$isEdit = isset($proposal) && !empty($proposal->id);
@endphp

@extends('mahasiswa.layouts.app')

@section('page-title', 'Progress Tugas Akhir')

@section('title', $isEdit ? 'Edit Revisi Proposal' : 'Pengajuan Proposal Baru')

@section('content')
    {{-- Flash messages are handled in the layout; avoid duplicating here. --}}
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

    <!-- Main Grid Layout for Form and Side Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start justify-center">
        <!-- Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 w-full">
                <a href="{{ route('mahasiswa.proposal.index') }}" class="text-gray-600 hover:text-gray-800 text-sm mb-4 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Proposal
                </a>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $isEdit ? 'Edit Revisi Proposal' : 'Form Pengajuan Proposal' }}
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    {{ $isEdit ? 'Perbarui informasi proposal Anda sesuai feedback dari dosen.' : 'Lengkapi semua informasi yang diperlukan untuk mengajukan proposal tugas akhir.' }}
                </p>

                <form id="proposalForm" method="POST" 
                      action="{{ $isEdit ? route('mahasiswa.proposal.update', $proposal->id) : route('mahasiswa.proposal.store') }}" 
                      enctype="multipart/form-data">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Tugas Akhir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" 
                            value="{{ old('judul', $proposal->judul ?? '') }}"
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
                            placeholder="Tuliskan Deskripsi Singkat atau Abstrak Proposal Anda" required>{{ old('deskripsi', $proposal->deskripsi ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 100 karakter</p>
                    </div>
                    <!-- Rumpun Ilmu -->
                    <div class="mb-6">
                        <label for="rumpun_ilmu" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rumpun Ilmu <span class="text-red-500">*</span>
                        </label>
                        <select id="rumpun_ilmu" name="rumpun_ilmu"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent" required>
                            <option value="">-- Pilih Rumpun Ilmu --</option>
                            @php
                                $selectedRumpun = old('rumpun_ilmu', $proposal->rumpun_ilmu ?? '');
                            @endphp
                            <option value="Fotografi" {{ $selectedRumpun == 'Fotografi' ? 'selected' : '' }}>Fotografi</option>
                            <option value="Film dan Televisi" {{ $selectedRumpun == 'Film dan Televisi' ? 'selected' : '' }}>Film dan Televisi</option>
                            <option value="Animasi" {{ $selectedRumpun == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                            <option value="Produksi Film dan Televisi" {{ $selectedRumpun == 'Produksi Film dan Televisi' ? 'selected' : '' }}>Produksi Film dan Televisi</option>
                        </select>
                    </div>
                    <!-- Dosen Pembimbing -->
                    <div class="mb-6">
                        <label for="dosen" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pilih Dosen Pembimbing
                        </label>
                        <select id="dosen" name="dosen_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @php
                                $selectedDosen = old('dosen_id', $proposal->dosen_id ?? '');
                            @endphp
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->nidn }}" {{ $selectedDosen == $dosen->nidn ? 'selected' : '' }}>
                                    {{ $dosen->nama }} - {{ $dosen->gelar ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- File Proposal -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            File Proposal (PDF) <span class="text-red-500">{{ $isEdit ? '' : '*' }}</span>
                        </label>
                        <div id="proposalArea" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-500 transition">
                            <i class="fas fa-cloud-upload-alt text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Drag & drop file skenario atau</p>
                            <button type="button" onclick="document.getElementById('fileProposal').click()" class="inline-block bg-yellow-700 text-white px-4 py-2 rounded-md font-medium">Pilih File</button>
                            <p class="text-xs text-gray-500 mt-3">Maksimal 10MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengubah file.' : '' }}</p>
                            <input type="file" id="fileProposal" name="file_proposal" accept=".pdf" class="hidden">
                            <div id="proposalFeedback" class="mt-2 hidden">
                                <span class="inline-flex items-center text-yellow-700 font-semibold"><i class="fas fa-check mr-2"></i> <span id="proposalFileName"></span></span>
                            </div>
                        </div>
                         @if($isEdit && !empty($proposal->file_proposal))
                            <p id="currentProposalFile" class="text-xs text-gray-600 mt-2">File saat ini: <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($proposal->file_proposal) }}</a></p>
                        @endif
                    </div>
                    <!-- File Pitch Deck -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            File Pitch Deck (PDF/PPT)
                        </label>
                         <div id="pitchDeckArea" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-500 transition">
                            <i class="fas fa-file-powerpoint text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau</p>
                            <button type="button" onclick="document.getElementById('filePitchDeck').click()" class="inline-block bg-yellow-700 text-white px-4 py-2 rounded-md font-medium">Pilih File</button>
                            <p class="text-xs text-gray-500 mt-3">PDF/PPT, maksimal 15 MB. {{ $isEdit ? 'Kosongkan jika tidak ingin mengubah file.' : '' }}</p>
                            <input type="file" id="filePitchDeck" name="file_pitch_deck" accept=".pdf,.ppt,.pptx" class="hidden">
                            <div id="pitchDeckFeedback" class="mt-2 hidden">
                                <span class="inline-flex items-center text-yellow-700 font-semibold"><i class="fas fa-check mr-2"></i> <span id="pitchDeckFileName"></span></span>
                            </div>
                        </div>
                         @if($isEdit && !empty($proposal->file_pitch_deck))
                            <p id="currentPitchDeckFile" class="text-xs text-gray-600 mt-2">File saat ini: <a href="{{ Storage::url($proposal->file_pitch_deck) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($proposal->file_pitch_deck) }}</a></p>
                        @endif
                    </div>
                    <!-- Tombol -->
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-800 transition">
                            @if($isEdit)
                                <i class="fas fa-sync-alt mr-2"></i>Update Revisi
                            @else
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Proposal
                            @endif
                        </button>
                        @if(!$isEdit)
                        <button type="button" onclick="saveDraft()"
                            class="px-6 py-3 border-2 border-yellow-700 text-yellow-700 rounded-lg font-semibold hover:bg-yellow-50 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Draft
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <!-- Download Template Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Template Laporan</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Unduh template laporan proposal terbaru untuk memastikan format yang benar.
                </p>
                <a href="#" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i> Unduh Template
                </a>
            </div>
        </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function humanFileSize(bytes) {
            if (!bytes) return '';
            var i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(i ? 1 : 0) + ['B','KB','MB','GB'][i];
        }

        function bindFileInput(inputId, feedbackId, fileNameId, currentSelector, maxBytes) {
            var input = document.getElementById(inputId);
            var feedback = document.getElementById(feedbackId);
            var fileNameEl = document.getElementById(fileNameId);
            var currentEl = currentSelector ? document.querySelector(currentSelector) : null;
            if (!input || !feedback || !fileNameEl) return;

            input.addEventListener('change', function (e) {
                var file = e.target.files[0];
                if (!file) {
                    feedback.classList.add('hidden');
                    if (currentEl) currentEl.style.display = '';
                    return;
                }
                if (maxBytes && file.size > maxBytes) {
                    alert('Ukuran file melebihi batas ' + (maxBytes/1024/1024) + 'MB');
                    input.value = '';
                    feedback.classList.add('hidden');
                    if (currentEl) currentEl.style.display = '';
                    return;
                }
                fileNameEl.textContent = file.name + ' (' + humanFileSize(file.size) + ')';
                feedback.classList.remove('hidden');
                if (currentEl) currentEl.style.display = 'none';
            });
        }

        bindFileInput('fileProposal','proposalFeedback','proposalFileName','#currentProposalFile',10*1024*1024);
        bindFileInput('filePitchDeck','pitchDeckFeedback','pitchDeckFileName','#currentPitchDeckFile',15*1024*1024);
    });
</script>

@endsection

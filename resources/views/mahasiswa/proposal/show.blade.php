@extends('mahasiswa.layouts.app')

@section('title', 'Detail Proposal - Tamago ISI')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 w-full max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <a href="{{ route('mahasiswa.proposal.index') }}" class="text-gray-600 hover:text-gray-800 text-sm mb-4 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Proposal
                </a>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $proposal->judul }}</h3>
                <p class="text-sm text-gray-500 mt-1">Versi {{ $proposal->versi }} - Diajukan pada {{ $proposal->tanggal_pengajuan->format('d F Y') }}</p>
            </div>
            <div class="flex-shrink-0">
                @php $badge = $proposal->statusBadge; @endphp
                <span class="{{ $badge['class'] }} px-4 py-2 text-md font-semibold rounded-full">
                    {{ $badge['text'] }}
                </span>
                @if($proposal->status == 'revisi')
                    <a href="{{ route('mahasiswa.proposal.edit', $proposal->id) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        <i class="fas fa-pencil-alt mr-2"></i>Edit Proposal
                    </a>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t pt-6">
            <!-- Left Column -->
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Deskripsi/Abstrak</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $proposal->deskripsi }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Rumpun Ilmu</h4>
                    <p class="text-gray-600 text-sm">{{ $proposal->rumpun_ilmu }}</p>
                </div>
                 @if($proposal->feedback)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Feedback dari Dosen</h4>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <p class="text-yellow-800 text-sm">{{ $proposal->feedback }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Dosen Pembimbing</h4>
                    @if($proposal->dosen)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 text-gray-600 flex items-center justify-center rounded-full font-bold">
                            {{ substr($proposal->dosen->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $proposal->dosen->nama }}</p>
                            <p class="text-xs text-gray-500">{{ $proposal->dosen->gelar ?? '' }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Belum ada dosen pembimbing</p>
                    @endif
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">File Proposal</h4>
                    <a href="{{ $proposal->file_proposal }}" class="flex items-center space-x-3 bg-gray-100 p-3 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">proposal_v{{$proposal->versi}}.pdf</p>
                            <p class="text-xs text-gray-500">Klik untuk melihat</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

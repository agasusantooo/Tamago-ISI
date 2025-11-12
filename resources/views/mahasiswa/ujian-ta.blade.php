@extends('mahasiswa.layouts.app')

@section('title', 'Ujian TA')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main column -->
        <div class="lg:col-span-8">
            <!-- Informasi Ujian Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Ujian</h2>
                <p class="text-sm text-gray-600 mb-4">Kelola dan upload file produksi tugas akhir Anda</p>

                {{-- Guidance alerts when requirements are not met --}}
                @if(!empty($missingProposal))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <div class="font-semibold text-red-700">Proposal belum disetujui</div>
                        <div class="text-sm text-gray-700 mt-1">Anda perlu mempunyai proposal yang disetujui terlebih dahulu sebelum dapat mendaftar ujian TA.</div>
                        <div class="mt-3">
                            <a href="{{ route('mahasiswa.proposal') }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded">Lihat Proposal</a>
                        </div>
                    </div>
                @endif

                @if(!empty($produksiNotApproved))
                    <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <div class="font-semibold text-yellow-800">Produksi akhir belum disetujui</div>
                        <div class="text-sm text-gray-700 mt-1">Produksi akhir Anda harus disetujui oleh dosen sebelum dapat mendaftar ujian.</div>
                        <div class="mt-3">
                            <a href="{{ route('mahasiswa.produksi.index') }}" class="inline-block px-4 py-2 bg-yellow-600 text-white rounded">Periksa Produksi</a>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="text-xs text-gray-600">Tanggal Ujian</div>
                        <div class="font-bold mt-2">15 Januari 2025</div>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="text-xs text-gray-600">Waktu</div>
                        <div class="font-bold mt-2">09:00 - 11:00 WIB</div>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="text-xs text-gray-600">Ruangan</div>
                        <div class="font-bold mt-2">Lab Multimedia A</div>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="text-sm text-gray-600 mb-2">Daftar Dosen Penguji</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="font-semibold">Dr. Ahmad Santoso, M.Kom</div>
                            <div class="text-xs text-gray-600">Ketua Penguji</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="font-semibold">Prof. Dr. Sari Indah, M.T</div>
                            <div class="text-xs text-gray-600">Penguji Ahli</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Ujian Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Pengajuan Ujian</h3>

                <!-- tombol hasil ujian dipindah ke kanan bawah status -->

                <div class="mb-4">
                    <div class="text-sm text-gray-600">Persyaratan Ujian</div>
                    <ul class="list-disc list-inside text-sm text-gray-700 mt-2">
                        <li>Sudah menyetujui semua tahapan produksi</li>
                        <li>Sudah upload draf naskah</li>
                        <li>SKS lulus (minimal C) adalah minimal 138 sks</li>
                    </ul>
                </div>

                <form action="{{ route('mahasiswa.ujian-ta.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Surat Pengantar</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF, DOC (Max 5MB)</p>
                                <input type="file" name="file_surat_pengantar" class="mx-auto" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip Nilai</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF (Max 5MB)</p>
                                <input type="file" name="file_transkrip_nilai" class="mx-auto" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Ajukan Ujian</button>
                    </div>
                </form>
            </div>
        </div>

    <!-- Right column -->
    <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                @livewire('mahasiswa.ujian-timeline')
            </div>

            {{-- status and action moved into livewire component for realtime updates --}}
        </div>
    </div>
    </div>
</div>
@endsection

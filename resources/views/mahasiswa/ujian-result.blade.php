@extends('mahasiswa.layouts.app')

@section('title', 'Hasil Ujian TA')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Hasil Ujian Tugas Akhir</h2>
                <p class="text-sm text-gray-600 mb-4">Status dan feedback hasil ujian tugas akhir Anda</p>

                {{-- Determine status and styling --}}
                @php
                    $statusUjian = strtolower(str_replace([' ', '-'], '_', $ujianTA->status_ujian ?? ''));
                    $statusPendaftaran = strtolower(str_replace([' ', '-'], '_', $ujianTA->status_pendaftaran ?? ''));
                    
                    if (strpos($statusUjian, 'lulus') !== false) {
                        $statusColor = 'green';
                        $statusLabel = 'Lulus';
                        $statusIcon = 'check-circle';
                    } elseif (strpos($statusUjian, 'tidak') !== false || strpos($statusUjian, 'gagal') !== false) {
                        $statusColor = 'red';
                        $statusLabel = 'Tidak Lulus';
                        $statusIcon = 'times-circle';
                    } else {
                        $statusColor = 'yellow';
                        $statusLabel = 'Perlu Revisi';
                        $statusIcon = 'exclamation-circle';
                    }
                @endphp

                <div class="bg-{{ $statusColor }}-50 border-l-4 border-{{ $statusColor }}-300 rounded p-4 mb-6">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-{{ $statusIcon }} text-{{ $statusColor }}-700 mt-1"></i>
                        <div>
                            <div class="text-{{ $statusColor }}-700 font-semibold">Status: {{ $statusLabel }}</div>
                            <div class="text-sm text-gray-700">
                                @if(strpos($statusUjian, 'lulus') !== false)
                                    Selamat! Ujian Anda dinyatakan lulus. Nilai akhir dan berita acara dapat didownload di bawah.
                                @elseif(strpos($statusUjian, 'revisi') !== false)
                                    Ujian Anda memerlukan beberapa perbaikan sebelum dapat dinyatakan lulus. Silakan lihat catatan feedback di bawah ini.
                                @else
                                    Ujian belum diproses. Silakan hubungi dosen jika ada pertanyaan.
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Show feedback if available --}}
                    @if(!empty($ujianTA->catatan_penguji))
                        <div class="mt-4 bg-white rounded p-4 border">
                            <h4 class="font-semibold mb-2">💬 Catatan dari Dosen Penguji:</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ujianTA->catatan_penguji }}</p>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    @if(!empty($ujianTA->file_berita_acara))
                        <a href="{{ route('mahasiswa.ujian-ta.download', [$ujianTA->id_ujian ?? $ujianTA->getKey(), 'berita_acara']) }}" class="inline-block text-sm text-blue-600 hover:underline">
                            <i class="fas fa-download mr-1"></i>Download Berita Acara Ujian
                        </a>
                    @endif
                </div>

                {{-- Revisi section only show if status needs revision --}}
                @if(strpos($statusUjian, 'revisi') !== false)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-semibold mb-3">Upload Revisi Pasca Ujian</h3>
                        <form action="{{ route('mahasiswa.ujian-ta.submit-revisi') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi Naskah/Karya</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                    <p class="text-sm text-gray-500 mb-2">PDF, DOC, DOCX (Max 50MB)</p>
                                    <input type="file" name="file_revisi" class="mx-auto" required />
                                </div>
                                @error('file_revisi')
                                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Revisi</label>
                                <textarea name="deskripsi_revisi" rows="5" class="w-full border rounded p-3 text-sm" placeholder="Jelaskan perbaikan yang dilakukan...">{{ old('deskripsi_revisi') }}</textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit Revisi</button>
                            </div>
                        </form>
                    </div>
                @elseif(strpos($statusUjian, 'lulus') !== false)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="text-center">
                            <i class="fas fa-check-circle text-green-600 text-4xl mb-3 block"></i>
                            <h3 class="font-semibold text-green-800 mb-2">Selamat Lulus!</h3>
                            <p class="text-sm text-gray-700">Ujian Anda telah dinyatakan lulus. Silakan download berita acara dan dokumen resmi lainnya.</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h4 class="font-semibold mb-3">Status Revisi:</h4>
                @php
                    $statusRevisi = strtolower(str_replace([' ', '-'], '_', $ujianTA->status_revisi ?? ''));
                @endphp
                <div class="px-3 py-4 rounded 
                    @if(strpos($statusRevisi, 'selesai') !== false) bg-green-50
                    @elseif(strpos($statusRevisi, 'persetujuan') !== false || strpos($statusRevisi, 'menunggu') !== false) bg-yellow-50
                    @else bg-gray-50 @endif">
                    <div class="font-semibold 
                        @if(strpos($statusRevisi, 'selesai') !== false) text-green-800
                        @elseif(strpos($statusRevisi, 'persetujuan') !== false || strpos($statusRevisi, 'menunggu') !== false) text-yellow-800
                        @else text-gray-800 @endif">
                        {{ ucwords(str_replace('_', ' ', $ujianTA->status_revisi ?? 'Tidak ada revisi')) }}
                    </div>
                    <div class="text-sm text-gray-600 mt-2">
                        @if(strpos($statusRevisi, 'selesai') !== false)
                            Revisi Anda telah disetujui. Hasil akhir sudah finalisasi.
                        @elseif(strpos($statusRevisi, 'persetujuan') !== false || strpos($statusRevisi, 'menunggu') !== false)
                            Revisi Anda sedang direview oleh dosen pembimbing.
                        @else
                            Belum ada revisi yang disubmit.
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="font-semibold mb-3">Ringkasan Hasil</h4>
                <div class="text-sm text-gray-700 space-y-2">
                    <p><strong>Status Ujian:</strong> {{ ucwords(str_replace('_', ' ', $ujianTA->status_ujian ?? '-')) }}</p>
                    <p><strong>Tanggal Ujian:</strong> {{ $ujianTA->tanggal_ujian?->format('d M Y') ?? '-' }}</p>
                    <p><strong>Nilai Akhir:</strong> {{ $ujianTA->hasil_akhir ?? $ujianTA->nilai_akhir ?? '-' }}</p>
                    <p><strong>Catatan:</strong> {{ $ujianTA->catatan_penguj ? 'Ada catatan' : 'Tidak ada catatan' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

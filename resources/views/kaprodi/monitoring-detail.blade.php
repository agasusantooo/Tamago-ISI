@extends('kaprodi.layouts.app')

@section('title', 'Detail Monitoring Mahasiswa')
@section('page-title', 'Detail Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <a href="{{ route('kaprodi.monitoring') }}" class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-800">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Halaman Monitoring
    </a>

    <!-- Student Info and Overall Progress -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-teal-600">{{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $mahasiswa->user->name ?? $mahasiswa->nama }}</h3>
                <p class="text-sm text-gray-500">{{ $mahasiswa->nim }}</p>
                <p class="text-sm mt-1">Status: 
                    <span class="font-semibold px-2 py-1 text-xs rounded-full 
                        @if(isset($displayStatus) && $displayStatus == 'lulus') bg-green-200 text-green-800
                        @elseif(isset($displayStatus) && $displayStatus == 'aktif') bg-yellow-200 text-yellow-800
                        @else bg-red-200 text-red-800
                        @endif
                    ">
                        {{ ucfirst($displayStatus ?? ($mahasiswa->status ?? '')) }}
                    </span>
                </p>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="text-md font-semibold text-gray-700">Progress Keseluruhan</h4>
            <div class="flex items-center mt-2">
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-teal-500 h-4 rounded-full" style="width: {{ $progressData['percentage'] }}%"></div>
                </div>
                <span class="ml-4 text-lg font-bold text-teal-600">{{ $progressData['percentage'] }}%</span>
            </div>
        </div>
    </div>

    <!-- Detailed Progress Stages -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-4">Rincian Tahapan Progress</h3>
        <div class="space-y-4">
            @forelse($progressData['details'] as $stage)
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full {{ $stage['fraction'] >= 1.0 ? 'bg-green-500' : 'bg-gray-300' }}">
                        @if($stage['fraction'] >= 1.0)
                            <i class="fas fa-check text-white"></i>
                        @else
                            <i class="fas fa-hourglass-half text-gray-600"></i>
                        @endif
                    </div>
                    <div class="ml-4 flex-grow">
                        <p class="font-medium text-gray-800">{{ $stage['name'] }}</p>
                        <p class="text-sm text-gray-500">Bobot: {{ $stage['weight'] }}%</p>
                    </div>
                    @if($stage['fraction'] >= 1.0)
                        <span class="text-sm font-semibold text-green-600">Selesai</span>
                    @else
                        <span class="text-sm font-semibold text-yellow-600">Dalam Proses ({{ ($stage['fraction'] * 100) }}%)</span>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500">Tidak ada data tahapan progress.</p>
            @endforelse
        </div>
    </div>

    <!-- Bimbingan History -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-4">Riwayat Bimbingan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bimbinganHistory as $bimbingan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bimbingan->tanggal ? \Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bimbingan->topik }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $bimbingan->status == 'disetujui' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                    {{ ucfirst($bimbingan->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada riwayat bimbingan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

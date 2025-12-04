@extends('mahasiswa.layouts.app')

@section('title', 'Tefa Fair - Tamago ISI')
@section('page-title', 'Tefa Fair')

@section('content')

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Jadwal & Persyaratan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Jadwal Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Tefa Fair</h2>
            @foreach($jadwalTefaFair as $jadwal)
                <div class="space-y-3 text-sm">
                    <p class="flex items-center text-gray-700"><i class="fas fa-calendar-alt fa-fw mr-2 text-green-600"></i><span class="font-semibold mr-1">Tanggal:</span> {{ $jadwal['tanggal'] }}</p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-clock fa-fw mr-2 text-green-600"></i><span class="font-semibold mr-1">Waktu:</span> {{ $jadwal['waktu'] }}</p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-map-marker-alt fa-fw mr-2 text-green-600"></i><span class="font-semibold mr-1">Tempat:</span> {{ $jadwal['tempat'] }}</p>
                    <p class="flex items-start text-gray-700"><i class="fas fa-info-circle fa-fw mr-2 mt-1 text-green-600"></i><span class="font-semibold mr-1">Deskripsi:</span> <span class="flex-1">{{ $jadwal['deskripsi'] }}</span></p>
                </div>
            @endforeach
        </div>

        <!-- Persyaratan Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list fa-fw mr-2 text-green-600"></i>
                Persyaratan
            </h3>
            <ul class="space-y-2 text-sm">
                @if(!empty($jadwalTefaFair[0]['persyaratan']))
                    @foreach($jadwalTefaFair[0]['persyaratan'] as $req)
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span>{{ $req }}</span>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    <!-- Registration History Table -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Daftar Tefa Fair</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar semua pendaftaran Tefa Fair yang pernah Anda ikuti.</p>
            </div>
            <a href="{{ route('mahasiswa.tefa-fair.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                <i class="fas fa-plus mr-2"></i>Daftar Tefa Fair
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($history as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->semester }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->statusBadge['class'] ?? '' }}">
                                    {{ $item->statusBadge['text'] ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-green-600 hover:text-green-900">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum pernah mendaftar Tefa Fair.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('mahasiswa.layouts.app')

@section('title', 'Riwayat Pendaftaran Story Conference - Tamago ISI')
@section('page-title', 'Story Conference')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Jadwal Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Story Conference</h2>
            @foreach($jadwalStoryConference as $jadwal)
                <div class="space-y-3 text-sm">
                    <p class="flex items-center text-gray-700"><i class="fas fa-calendar-alt fa-fw mr-2 text-purple-600"></i><span class="font-semibold mr-1">Tanggal:</span> {{ $jadwal['tanggal'] }}</p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-clock fa-fw mr-2 text-purple-600"></i><span class="font-semibold mr-1">Waktu:</span> {{ $jadwal['waktu'] }}</p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-map-marker-alt fa-fw mr-2 text-purple-600"></i><span class="font-semibold mr-1">Tempat:</span> {{ $jadwal['tempat'] }}</p>
                    <p class="flex items-start text-gray-700"><i class="fas fa-info-circle fa-fw mr-2 mt-1 text-purple-600"></i><span class="font-semibold mr-1">Deskripsi:</span> <span class="flex-1">{{ $jadwal['deskripsi'] }}</span></p>
                </div>
            @endforeach
        </div>

        <!-- Persyaratan Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list fa-fw mr-2 text-purple-600"></i>
                Persyaratan
            </h3>
            <ul class="space-y-2 text-sm">
                @if(!empty($jadwalStoryConference[0]['persyaratan']))
                    @foreach($jadwalStoryConference[0]['persyaratan'] as $req)
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span class="text-gray-700">{{ $req }}</span>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Daftar Story Conference</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar semua story conference yang pernah Anda ikuti.</p>
            </div>
            <a href="{{ route('mahasiswa.story-conference.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>Daftar Story Conference
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">Judul Karya</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal Daftar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Slot Waktu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($history as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($item->judul_karya, 40) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal_daftar ? $item->tanggal_daftar->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->slot_waktu }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->statusBadge['class'] ?? '' }}">
                                    {{ $item->statusBadge['text'] ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-purple-600 hover:text-purple-900">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum pernah mendaftar story conference.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

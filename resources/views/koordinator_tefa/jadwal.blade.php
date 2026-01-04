@extends('koordinator_tefa.layouts.app')

@section('title', 'Setup Jadwal TEFA Fair')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-xl font-bold text-green-800 mb-6">Setup Jadwal TEFA Fair</h3>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('koordinator_tefa.jadwal.store') }}" method="POST">
            @csrf
            <p class="text-gray-600 mb-4">Gunakan halaman ini untuk mengatur satu jadwal utama untuk kegiatan TEFA Fair.</p>
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Nama Acara:</label>
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" value="{{ old('title', $jadwal->title ?? 'TEFA Fair') }}" required>
                @error('title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="start" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai:</label>
                <input type="date" name="start" id="start" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start') border-red-500 @enderror" value="{{ old('start', $jadwal->start ? \Carbon\Carbon::parse($jadwal->start)->format('Y-m-d') : '') }}" required>
                @error('start')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="end" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Berakhir:</label>
                <input type="date" name="end" id="end" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end') border-red-500 @enderror" value="{{ old('end', $jadwal->end ? \Carbon\Carbon::parse($jadwal->end)->format('Y-m-d') : '') }}" required>
                @error('end')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-start">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>

    {{-- Jadwal history --}}
    <div class="max-w-4xl mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Jadwal TEFA Fair</h3>

            @if(!empty($history) && $history->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Acara</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Disimpan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($history as $h)
                                <tr @if(isset($jadwal->id) && $jadwal->id === $h->id) class="bg-yellow-50" @endif>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $h->title }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($h->start)->format('d M Y') }}{{ $h->end ? ' - ' . \Carbon\Carbon::parse($h->end)->format('d M Y') : '' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $h->created_at ? $h->created_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada jadwal TEFA yang tersimpan.</p>
            @endif
        </div>
    </div>
</div>
@endsection

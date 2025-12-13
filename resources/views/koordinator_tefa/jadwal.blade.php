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
</div>
@endsection

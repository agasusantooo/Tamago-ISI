@extends('kaprodi.layouts.app')

@section('title', 'Setup Dosen Seminar')
@section('page-title', 'Setup Dosen Seminar')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Session Messages -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-1">Pengelolaan Dosen Mata Kuliah Seminar</h3>
        <p class="text-sm text-gray-600 mb-4">Pilih dosen yang akan mengampu mata kuliah Seminar.</p>
        
        <form action="{{ route('kaprodi.dosen-seminar.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($dosens as $dosen)
                        <div class="flex items-center">
                            <input id="dosen_{{ $dosen->nidn }}" name="dosen_nidns[]" type="checkbox" value="{{ $dosen->nidn }}"
                                   {{ $dosen->is_dosen_seminar ? 'checked' : '' }}
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="dosen_{{ $dosen->nidn }}" class="ml-3 block text-sm font-medium text-gray-700">
                                {{ $dosen->user->name ?? $dosen->nama }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end pt-6">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Dosen Seminar</button>
            </div>
        </form>
    </div>
</div>
@endsection

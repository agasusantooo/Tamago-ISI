@extends('kaprodi.layouts.app')

@section('title', 'Setup Koordinator TEFA')
@section('page-title', 'Setup Koordinator TEFA')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Session Messages -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-1">Pengelolaan Koordinator TEFA Fair</h3>
        <p class="text-sm text-gray-600 mb-4">Pilih satu dosen yang akan menjadi koordinator untuk kegiatan TEFA Fair.</p>
        
        <form action="{{ route('kaprodi.koordinator-tefa.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Koordinator TEFA</label>
                    <select id="user_id" name="user_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->user->id }}" 
                                    {{ optional($currentCoordinator)->id == $dosen->user->id ? 'selected' : '' }}>
                                {{ $dosen->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end pt-6">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Koordinator</button>
            </div>
        </form>

        @if($currentCoordinator)
        <div class="mt-8 pt-6 border-t">
            <h4 class="text-md font-semibold text-gray-700">Koordinator TEFA Saat Ini</h4>
            <p class="text-lg text-teal-700 font-bold mt-2">{{ $currentCoordinator->name }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

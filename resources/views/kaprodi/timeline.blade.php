@extends('kaprodi.layouts.app')

@section('title', 'Pengaturan Semester Kegiatan')
@section('page-title', 'Setup Semester Kegiatan')

@section('content')
    <div class="max-w-4xl mx-auto">

        <!-- Session Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Sukses</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-teal-800 mb-4">Tentukan Semester untuk Kegiatan Akademik</h3>
            <p class="text-sm text-gray-600 mb-6">Pilih semester di mana kegiatan Seminar, TEFA Fair, dan Tugas Akhir akan dilaksanakan.</p>
            
            <form action="{{ route('kaprodi.timeline.store') }}" method="POST" class="space-y-6">
                @csrf

                @foreach($activityTypes as $typeKey => $typeName)
                    <div>
                        <label for="{{ $typeKey }}_semester_id" class="block text-sm font-medium text-gray-700">Semester untuk {{ $typeName }}</label>
                        <select id="{{ $typeKey }}_semester_id" name="{{ $typeKey }}_semester_id" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            <option value="">-- Tidak Ditentukan --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" 
                                        {{ optional($activitySemesters->get($typeKey))->semester_id == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error($typeKey . '_semester_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Pengaturan Semester Kegiatan</button>
                </div>
            </form>

        </div>
    </div>
@endsection

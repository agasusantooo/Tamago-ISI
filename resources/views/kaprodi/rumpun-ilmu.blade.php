@extends('kaprodi.layouts.app')

@section('title', 'Setup Rumpun Ilmu')
@section('page-title', 'Setup Rumpun Ilmu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Session Messages -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @forelse($rumpunIlmus as $rumpun)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-3">{{ $rumpun->nama }}</h4>
                    <ul class="space-y-2">
                        @forelse($rumpun->dosens as $dosen)
                            <li class="text-sm text-gray-700">{{ $dosen->user->name ?? $dosen->nama }} ({{ $dosen->nidn }})</li>
                        @empty
                            <li class="text-sm text-gray-500 italic">Belum ada dosen di rumpun ilmu ini.</li>
                        @endforelse
                    </ul>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                    <p class="text-gray-500">Belum ada rumpun ilmu yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
        
        <div class="space-y-6">
            <!-- Form to Add New Rumpun Ilmu -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-teal-800 mb-4">Tambah Rumpun Ilmu</h3>
                <form action="{{ route('kaprodi.rumpun-ilmu.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Rumpun Ilmu</label>
                        <input type="text" id="nama" name="nama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Tambah</button>
                    </div>
                </form>
            </div>

            <!-- Form to Assign Dosen -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-teal-800 mb-4">Tambahkan Dosen ke Rumpun Ilmu</h3>
                <form action="{{ route('kaprodi.rumpun-ilmu.assign') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rumpun_ilmu_id" class="block text-sm font-medium text-gray-700">Pilih Rumpun Ilmu</label>
                        <select id="rumpun_ilmu_id" name="rumpun_ilmu_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($rumpunIlmus as $rumpun)
                                <option value="{{ $rumpun->id }}">{{ $rumpun->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="dosen_nidn" class="block text-sm font-medium text-gray-700">Pilih Dosen</label>
                        <select id="dosen_nidn" name="dosen_nidn" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->nidn }}">{{ $dosen->user->name ?? $dosen->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Tetapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

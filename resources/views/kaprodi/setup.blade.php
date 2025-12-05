@extends('kaprodi.layouts.app')

@section('title', 'Setup Periode Semester')
@section('page-title', 'Setup Semester')

@section('content')
    <div class="max-w-5xl mx-auto">

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

        <!-- Form to Add New Semester -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-teal-800 mb-4">Tambah Periode Semester Baru</h3>
            
            <form action="{{ route('kaprodi.semester.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Semester</label>
                    <input type="text" id="nama" name="nama" placeholder="e.g., Gasal 2024/2025" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai Semester</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Akhir Semester</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Jadikan semester ini aktif</label>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Semester</button>
                </div>
            </form>
        </div>

        <!-- Table of Existing Semesters -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-teal-800 mb-4">Daftar Periode Semester</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Akhir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($semesters as $semester)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $semester->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($semester->tanggal_akhir)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if($semester->is_active)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-teal-600 hover:text-teal-900">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada periode semester yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

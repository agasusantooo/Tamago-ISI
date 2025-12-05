@extends('dospem.layouts.app')

@section('title', 'Dashboard Dosen Pembimbing')

@section('content')
                    <!-- Statistik -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-blue-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Mahasiswa Aktif</p>
                                    <p class="text-3xl font-bold text-blue-600">{{ $mahasiswaAktifCount }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-sky-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Tugas Review</p>
                                    <p class="text-3xl font-bold text-sky-600">{{ $tugasReview }}</p>
                                </div>
                                <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-sky-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-teal-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Bimbingan Minggu Ini</p>
                                    <p class="text-3xl font-bold text-teal-600">{{ $bimbinganMingguIni }}</p>
                                </div>
                                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-teal-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-indigo-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">TA Selesai</p>
                                    <p class="text-3xl font-bold text-indigo-600">{{ $taSelesai }}</p>
                                </div>
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-trophy text-indigo-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konten Utama -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Mahasiswa Bimbingan Aktif</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-blue-50 border-b border-blue-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">NIM</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Judul TA</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Progress</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-blue-50">
                                            @forelse($mahasiswaBimbingan as $mhs)
                                            <tr class="hover:bg-blue-50 transition">
                                                <td class="px-4 py-3 text-sm text-gray-700">{{ $mhs->nim }}</td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span class="font-bold text-blue-600">{{ strtoupper(substr($mhs->name, 0, 1)) }}</span>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-blue-900">{{ $mhs->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $mhs->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <p class="text-sm text-gray-700">{{ $mhs->judul_ta ?? 'Belum ada judul' }}</p>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $mhs->progress }}%"></div>
                                                        </div>
                                                        <span class="text-sm font-bold text-blue-600">{{ $mhs->progress }}%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada mahasiswa bimbingan</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Tugas Menunggu Review</h3>
                                <div class="space-y-3">
                                    @forelse($tugasMenungguReview as $tugas)
                                    <div class="flex items-center justify-between p-4 bg-sky-50 border-l-4 border-sky-400 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-blue-900">{{ $tugas->judul }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $tugas->mahasiswa_nim }} - {{ $tugas->mahasiswa_name }}</p>
                                            <p class="text-xs text-sky-600 mt-1">Diajukan: {{ $tugas->created_at }}</p>
                                        </div>
                                        <button class="px-4 py-2 text-sm bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                            Review
                                        </button>
                                    </div>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">Tidak ada tugas yang perlu direview</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Jadwal Bimbingan Mendatang</h3>
                                <div class="space-y-3">
                                    @forelse($jadwalBimbingan as $jadwal)
                                    <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                                        <p class="font-semibold text-blue-900 text-sm">{{ $jadwal->mahasiswa_nim }} - {{ $jadwal->mahasiswa_name }}</p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            <i class="far fa-calendar mr-1"></i>{{ $jadwal->tanggal }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $jadwal->topik }}</p>
                                    </div>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">Tidak ada jadwal bimbingan</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
@endsection
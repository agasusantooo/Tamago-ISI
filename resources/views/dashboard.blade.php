<x-app-layout>
    {{-- Bagian Header Halaman (Opsional, sesuaikan dengan layout Anda) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Konten Utama Halaman Dashboard --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{-- Ganti dengan data user yang login --}}
                            Selamat datang, Ahmad Rizki
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            NIM: 2021110001
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Progres Tugas Akhir</h3>
                            <div class="flex justify-center items-center">
                                <svg class="w-48 h-48" viewBox="0 0 36 36">
                                    <path class="text-gray-200 dark:text-gray-700"
                                        d="M18 2.0845
                                          a 15.9155 15.9155 0 0 1 0 31.831
                                          a 15.9155 15.9155 0 0 1 0 -31.831"
                                        fill="none" stroke="currentColor" stroke-width="3" />
                                    <path class="text-blue-600"
                                        d="M18 2.0845
                                          a 15.9155 15.9155 0 0 1 0 31.831"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        stroke-dasharray="75, 100" {{-- Ubah nilai pertama (75) untuk progress. 75 = 75% --}}
                                    />
                                </svg>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-center">
                                <span class="text-blue-600 dark:text-blue-400 font-semibold">● Proposal</span>
                                <span class="text-blue-600 dark:text-blue-400 font-semibold">● Bimbingan</span>
                                <span class="text-gray-500">● Produksi</span>
                                <span class="text-gray-500">● Ujian Tugas Akhir</span>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dosen Pembimbing</h3>
                            <div class="space-y-2">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">Dr. Sarah Wijaya, S.Kom., M.T.</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">sarah.wijaya@univ.ac.id</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">+62 812-3456-7890</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tugas & Deadline Mendatang</h3>
                        {{-- Loop melalui data tugas dari backend --}}
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">Upload Revisi Proposal</p>
                                    <p class="text-xs text-red-600 dark:text-red-400">Deadline: 25 Mar 2024, 23:59</p>
                                </div>
                                <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Kerjakan</a>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">Pendaftaran Story Conference</p>
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400">Deadline: 30 Mar 2024, 17:00</p>
                                </div>
                                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-md hover:bg-yellow-500">Daftar</a>
                            </div>
                             <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">Submit Laporan Bulanan</p>
                                    <p class="text-xs text-gray-500">Jadwal: 28 Mar 2024, 10:00</p>
                                </div>
                                <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pengumuman Terbaru</h3>
                        {{-- Loop melalui data pengumuman dari backend --}}
                        <div class="space-y-4">
                            <div class="p-3 bg-pink-50 dark:bg-pink-900/20 border-l-4 border-pink-400">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">Jadwal Seminar Proposal</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">16 September 2024 Dr. Ahmad Rahman</p>
                            </div>
                            {{-- Tambahkan pengumuman lain di sini --}}
                        </div>
                        <a href="#" class="mt-6 w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-md hover:bg-yellow-500">
                            Lihat Semua Pengumuman
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
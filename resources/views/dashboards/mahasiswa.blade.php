<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
                transition: transform 0.3s ease-in-out;
            }
            #sidebar.hidden-mobile {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="bg-yellow-50">

    <div class="flex h-screen overflow-hidden">
        <!-- ✅ Sidebar Partial -->
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <!-- ✅ Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- ✅ Header Partial -->
            @include('mahasiswa.partials.header-mahasiswa')

            <!-- ✅ Main Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    Selamat datang, <span class="uppercase">{{ Auth::user()->name }}</span> 👋
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">NIM: 2021110001</p>
                            </div>

                            <!-- Progres Tugas Akhir -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">Progres Tugas Akhir</h3>
                                        <button onclick="toggleProgressDetail()" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                                            <i class="fas fa-list mr-1"></i>Detail
                                        </button>
                                    </div>
                                    <div class="flex justify-center items-center">
                                        <div class="relative w-40 h-40">
                                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                                                <circle cx="50" cy="50" r="45" fill="none" stroke="#FACC15" stroke-width="8"
                                                    stroke-dasharray="282.6" stroke-dashoffset="70.65" stroke-linecap="round"/>
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-3xl font-bold text-yellow-600">75%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                            <span class="text-yellow-600 font-medium">Proposal</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                            <span class="text-yellow-600 font-medium">Bimbingan</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                                            <span class="text-gray-500">Produksi</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                                            <span class="text-gray-500">Ujian TA</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dosen -->
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dosen Pembimbing</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full font-bold">
                                                SW
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">Dr. Sarah Wijaya, S.Kom., M.T.</p>
                                                <p class="text-xs text-gray-500">Pembimbing Utama</p>
                                            </div>
                                        </div>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center space-x-2 text-gray-600">
                                                <i class="fas fa-envelope w-4"></i>
                                                <span>sarah.wijaya@isi.ac.id</span>
                                            </div>
                                            <div class="flex items-center space-x-2 text-gray-600">
                                                <i class="fas fa-phone w-4"></i>
                                                <span>+62 812-3456-7890</span>
                                            </div>
                                        </div>
                                        <button class="w-full mt-3 px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition">
                                            <i class="fas fa-calendar-alt mr-2"></i>Buat Jadwal Bimbingan
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas & Deadline -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tugas & Deadline Mendatang</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Upload Revisi Proposal</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                <i class="far fa-clock mr-1"></i>Deadline: 25 Mar 2024, 23:59
                                            </p>
                                        </div>
                                        <button class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                            Kerjakan
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-yellow-100 border-l-4 border-yellow-600 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Pendaftaran Story Conference</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                <i class="far fa-clock mr-1"></i>Deadline: 30 Mar 2024, 17:00
                                            </p>
                                        </div>
                                        <button class="px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-lg hover:bg-yellow-500">
                                            Daftar
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Submit Laporan Bulanan</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                <i class="far fa-calendar mr-1"></i>Jadwal: 28 Mar 2024, 10:00
                                            </p>
                                        </div>
                                        <button class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman Terbaru</h3>
                                <div class="space-y-3">
                                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Jadwal Seminar Proposal</p>
                                        <p class="text-xs text-gray-600 mt-1">16 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Dr. Ahmad Rahman</p>
                                    </div>
                                    <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Perpanjangan Deadline</p>
                                        <p class="text-xs text-gray-600 mt-1">20 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Prodi Film & TV</p>
                                    </div>
                                    <div class="p-4 bg-yellow-200 border-l-4 border-yellow-600 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Workshop Produksi</p>
                                        <p class="text-xs text-gray-600 mt-1">22 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Studio Production</p>
                                    </div>
                                </div>
                                <button class="mt-4 w-full px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-lg hover:bg-yellow-500 transition">
                                    Lihat Semua Pengumuman
                                </button>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Cepat</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Bimbingan</span>
                                        <span class="text-lg font-bold text-yellow-600">12</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Tugas Selesai</span>
                                        <span class="text-lg font-bold text-yellow-700">8/10</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">File Terupload</span>
                                        <span class="text-lg font-bold text-yellow-500">23</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("hidden-mobile");
        });
    </script>
</body>
</html>

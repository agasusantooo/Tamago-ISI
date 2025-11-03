<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col hidden-mobile" id="sidebar">
            <!-- Logo -->
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-900 text-white flex items-center justify-center rounded-lg font-bold text-xs">
                        ISI<br>YK
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                        <p class="text-xs text-gray-500">Sistem TA</p>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-3 px-4 py-3 text-blue-900 bg-blue-50 rounded-lg font-medium">
                            <i class="fas fa-home w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-file-alt w-5"></i>
                            <span>Pengajuan Proposal</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-chalkboard-teacher w-5"></i>
                            <span>Bimbingan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-comments w-5"></i>
                            <span>Story Conference</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-film w-5"></i>
                            <span>Produksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-graduation-cap w-5"></i>
                            <span>Ujian TA</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-folder-open w-5"></i>
                            <span>Naskah & Karya</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-user w-5"></i>
                            <span>Akun Saya</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-full font-semibold">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate uppercase"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="text-gray-600 hover:text-gray-800 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-semibold text-gray-800 uppercase"><?php echo e(Auth::user()->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></p>
                        </div>
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column (2/3) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Welcome Card -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    Selamat datang, <span class="uppercase"><?php echo e(Auth::user()->name); ?></span> 👋
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    NIM: 2021110001
                                </p>
                            </div>

                            <!-- Progress & Dosen -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Progress Card -->
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Progres Tugas Akhir</h3>
                                    <div class="flex justify-center items-center">
                                        <div class="relative w-40 h-40">
                                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                                                <circle cx="50" cy="50" r="45" fill="none" stroke="#2563eb" stroke-width="8"
                                                    stroke-dasharray="282.6" stroke-dashoffset="70.65" stroke-linecap="round"/>
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-3xl font-bold text-blue-600">75%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                            <span class="text-blue-600 font-medium">Proposal</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                            <span class="text-blue-600 font-medium">Bimbingan</span>
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

                                <!-- Dosen Pembimbing Card -->
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dosen Pembimbing</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-blue-100 text-blue-600 flex items-center justify-center rounded-full font-bold">
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
                                        <button class="w-full mt-3 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-calendar-alt mr-2"></i>Buat Jadwal Bimbingan
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas & Deadline -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tugas & Deadline Mendatang</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Upload Revisi Proposal</p>
                                            <p class="text-xs text-red-600 mt-1">
                                                <i class="far fa-clock mr-1"></i>Deadline: 25 Mar 2024, 23:59
                                            </p>
                                        </div>
                                        <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                            Kerjakan
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Pendaftaran Story Conference</p>
                                            <p class="text-xs text-yellow-600 mt-1">
                                                <i class="far fa-clock mr-1"></i>Deadline: 30 Mar 2024, 17:00
                                            </p>
                                        </div>
                                        <button class="px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-lg hover:bg-yellow-500">
                                            Daftar
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Submit Laporan Bulanan</p>
                                            <p class="text-xs text-blue-600 mt-1">
                                                <i class="far fa-calendar mr-1"></i>Jadwal: 28 Mar 2024, 10:00
                                            </p>
                                        </div>
                                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column (1/3) -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Pengumuman -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman Terbaru</h3>
                                <div class="space-y-3">
                                    <div class="p-4 bg-pink-50 border-l-4 border-pink-400 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Jadwal Seminar Proposal</p>
                                        <p class="text-xs text-gray-600 mt-1">16 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Dr. Ahmad Rahman</p>
                                    </div>
                                    <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Perpanjangan Deadline</p>
                                        <p class="text-xs text-gray-600 mt-1">20 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Prodi Film & TV</p>
                                    </div>
                                    <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Workshop Produksi</p>
                                        <p class="text-xs text-gray-600 mt-1">22 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Studio Production</p>
                                    </div>
                                </div>
                                <button class="mt-4 w-full px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-lg hover:bg-yellow-500 transition">
                                    Lihat Semua Pengumuman
                                </button>
                            </div>

                            <!-- Quick Stats -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Cepat</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Bimbingan</span>
                                        <span class="text-lg font-bold text-blue-600">12</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Tugas Selesai</span>
                                        <span class="text-lg font-bold text-green-600">8/10</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">File Terupload</span>
                                        <span class="text-lg font-bold text-purple-600">24</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <script>
        // Toggle Sidebar for Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hidden-mobile');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.add('hidden-mobile');
            overlay.classList.add('hidden');
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('hidden-mobile');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('hidden-mobile');
            }
        });
    </script>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/dashboard.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen Pembimbing - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col border-r border-blue-100" id="sidebar">
            <!-- Logo -->
            <div class="p-6 border-b border-blue-100">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-700 text-white flex items-center justify-center rounded-lg font-bold text-xs">
                        ISI<br>YK
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-blue-800">Tamago ISI</h1>
                        <p class="text-xs text-blue-500">Dosen Pembimbing</p>
                    </div>
                </div>
            </div>

            <!-- Navigasi -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="<?php echo e(route('dashboard')); ?>"
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition 
                           <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                            <i class="fas fa-home w-5"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('dospem.mahasiswa-bimbingan')); ?>"
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           <?php echo e(request()->routeIs('dospem.mahasiswa-bimbingan') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                            <i class="fas fa-users w-5"></i><span>Mahasiswa Bimbingan</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('dospem.jadwal-bimbingan')); ?>" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-calendar-alt w-5"></i><span>Jadwal Bimbingan</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('dospem.review-tugas')); ?>" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-tasks w-5"></i><span>Review Tugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-file-alt w-5"></i><span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition">
                            <i class="fas fa-user w-5"></i><span>Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Info -->
            <div class="p-4 border-t border-blue-100">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-full font-semibold">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-blue-800 truncate uppercase"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-blue-100">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-bold text-blue-800">Dashboard Dosen Pembimbing</h2>
                    <div class="flex items-center space-x-4">
                        <p class="text-sm font-semibold uppercase text-blue-700"><?php echo e(Auth::user()->name); ?></p>
                        <button class="relative p-2 text-blue-700 hover:bg-blue-100 rounded-lg">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">

                    <!-- Statistik -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-blue-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Mahasiswa Aktif</p>
                                    <p class="text-3xl font-bold text-blue-600"><?php echo e($mahasiswaAktifCount); ?></p>
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
                                    <p class="text-3xl font-bold text-sky-600"><?php echo e($tugasReview); ?></p>
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
                                    <p class="text-3xl font-bold text-teal-600"><?php echo e($bimbinganMingguIni); ?></p>
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
                                    <p class="text-3xl font-bold text-indigo-600"><?php echo e($taSelesai); ?></p>
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
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Judul TA</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Progress</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-blue-50">
                                            <?php $__empty_1 = true; $__currentLoopData = $mahasiswaBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="hover:bg-blue-50 transition">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span class="font-bold text-blue-600"><?php echo e(strtoupper(substr($mhs->name, 0, 1))); ?></span>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-blue-900"><?php echo e($mhs->name); ?></p>
                                                            <p class="text-xs text-gray-500"><?php echo e($mhs->email); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <p class="text-sm text-gray-700"><?php echo e($mhs->judul_ta ?? 'Belum ada judul'); ?></p>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($mhs->progress); ?>%"></div>
                                                        </div>
                                                        <span class="text-sm font-bold text-blue-600"><?php echo e($mhs->progress); ?>%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada mahasiswa bimbingan</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Tugas Menunggu Review</h3>
                                <div class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $tugasMenungguReview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="flex items-center justify-between p-4 bg-sky-50 border-l-4 border-sky-400 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-blue-900"><?php echo e($tugas->judul); ?></p>
                                            <p class="text-xs text-gray-600 mt-1"><?php echo e($tugas->mahasiswa_name); ?></p>
                                            <p class="text-xs text-sky-600 mt-1">Diajukan: <?php echo e($tugas->created_at); ?></p>
                                        </div>
                                        <button class="px-4 py-2 text-sm bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                            Review
                                        </button>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-center text-gray-500 py-4">Tidak ada tugas yang perlu direview</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Jadwal Bimbingan Mendatang</h3>
                                <div class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $jadwalBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                                        <p class="font-semibold text-blue-900 text-sm"><?php echo e($jadwal->mahasiswa_name); ?></p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            <i class="far fa-calendar mr-1"></i><?php echo e($jadwal->tanggal); ?>

                                        </p>
                                        <p class="text-xs text-gray-600 mt-1"><?php echo e($jadwal->topik); ?></p>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-center text-gray-500 py-4">Tidak ada jadwal bimbingan</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\tam\ooo\Tamago-ISI-main\resources\views/dashboards/pembimbing.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Bimbingan - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('dospem.partials.sidebar-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('dospem.partials.header-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <!-- Header Section -->
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-blue-800 mb-1">Daftar Mahasiswa</h1>
                            <div class="text-sm text-gray-600">Mahasiswa Aktif: <span class="font-semibold text-blue-700"><?php echo e($mahasiswaAktifCount ?? '-'); ?></span> | Tugas Review: <span class="font-semibold text-blue-700"><?php echo e($tugasReview ?? '-'); ?></span></div>
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-blue-800">Daftar Mahasiswa</h3>
                            <div class="flex items-center space-x-2">
                                <input type="text" placeholder="Cari mahasiswa..." class="px-4 py-2 border rounded-lg text-sm">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50 border-b border-blue-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">NIM</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Judul TA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Progress</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Bimbingan Terakhir</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-50">
                                    <?php if(isset($mahasiswaBimbingan) && count($mahasiswaBimbingan)): ?>
                                        <?php $__empty_1 = true; $__currentLoopData = $mahasiswaBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="hover:bg-blue-50 transition">
                                                <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($mhs->nim); ?></td>
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
                                                <td class="px-4 py-3 text-center text-sm text-gray-600"><?php echo e($mhs->bimbingan_terakhir); ?></td>
                                                <td class="px-4 py-3 text-center">
                                                    <a href="<?php echo e(route('dospem.mahasiswa-bimbingan.show', ['id' => $mhs->nim ?? $mhs->id])); ?>" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data mahasiswa bimbingan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-gray-500">Data mahasiswa bimbingan tidak tersedia.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\Tamago-ISI\resources\views/dospem/mahasiswa-bimbingan.blade.php ENDPATH**/ ?>
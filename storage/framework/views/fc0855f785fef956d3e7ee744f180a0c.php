<?php $__env->startSection('title', 'Detail Monitoring Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Detail Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto space-y-6">

    <a href="<?php echo e(route('kaprodi.monitoring')); ?>" class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-800">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Halaman Monitoring
    </a>

    <!-- Student Info and Overall Progress -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-teal-600"><?php echo e(strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1))); ?></span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800"><?php echo e($mahasiswa->user->name ?? $mahasiswa->nama); ?></h3>
                <p class="text-sm text-gray-500"><?php echo e($mahasiswa->nim); ?></p>
                <p class="text-sm mt-1">Status: 
                    <span class="font-semibold px-2 py-1 text-xs rounded-full <?php echo e($mahasiswa->status == 'aktif' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'); ?>">
                        <?php echo e(ucfirst($mahasiswa->status)); ?>

                    </span>
                </p>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="text-md font-semibold text-gray-700">Progress Keseluruhan</h4>
            <div class="flex items-center mt-2">
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-teal-500 h-4 rounded-full" style="width: <?php echo e($progressData['percentage']); ?>%"></div>
                </div>
                <span class="ml-4 text-lg font-bold text-teal-600"><?php echo e($progressData['percentage']); ?>%</span>
            </div>
        </div>
    </div>

    <!-- Detailed Progress Stages -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-4">Rincian Tahapan Progress</h3>
        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $progressData['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full <?php echo e($stage['fraction'] >= 1.0 ? 'bg-green-500' : 'bg-gray-300'); ?>">
                        <?php if($stage['fraction'] >= 1.0): ?>
                            <i class="fas fa-check text-white"></i>
                        <?php else: ?>
                            <i class="fas fa-hourglass-half text-gray-600"></i>
                        <?php endif; ?>
                    </div>
                    <div class="ml-4 flex-grow">
                        <p class="font-medium text-gray-800"><?php echo e($stage['name']); ?></p>
                        <p class="text-sm text-gray-500">Bobot: <?php echo e($stage['weight']); ?>%</p>
                    </div>
                    <?php if($stage['fraction'] >= 1.0): ?>
                        <span class="text-sm font-semibold text-green-600">Selesai</span>
                    <?php else: ?>
                        <span class="text-sm font-semibold text-yellow-600">Dalam Proses (<?php echo e(($stage['fraction'] * 100)); ?>%)</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500">Tidak ada data tahapan progress.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bimbingan History -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-4">Riwayat Bimbingan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $bimbinganHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($bimbingan->tanggal ? \Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y') : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($bimbingan->topik); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo e($bimbingan->status == 'disetujui' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800'); ?>">
                                    <?php echo e(ucfirst($bimbingan->status)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada riwayat bimbingan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/kaprodi/monitoring-detail.blade.php ENDPATH**/ ?>
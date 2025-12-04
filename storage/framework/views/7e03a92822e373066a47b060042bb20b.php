<?php $__env->startSection('title', 'Tefa Fair - Tamago ISI'); ?>
<?php $__env->startSection('page-title', 'Tefa Fair'); ?>

<?php $__env->startSection('content'); ?>

    <!-- Alerts -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-green-700"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-red-700"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>

    <!-- Jadwal & Persyaratan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Jadwal Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Tefa Fair</h2>
            <?php $__currentLoopData = $jadwalTefaFair; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="space-y-3 text-sm">
                    <p class="flex items-center text-gray-700"><i class="fas fa-calendar-alt fa-fw mr-2 text-green-600"></i><span class="font-semibold mr-1">Tanggal:</span> <?php echo e($jadwal['tanggal']); ?></p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-clock fa-fw mr-2 text-green-600"></i><span class="font-semibold mr-1">Waktu:</span> <?php echo e($jadwal['waktu']); ?></p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-map-marker-alt fa-fw mr-2 text-green-600"></i><span class="font-semibold mr-1">Tempat:</span> <?php echo e($jadwal['tempat']); ?></p>
                    <p class="flex items-start text-gray-700"><i class="fas fa-info-circle fa-fw mr-2 mt-1 text-green-600"></i><span class="font-semibold mr-1">Deskripsi:</span> <span class="flex-1"><?php echo e($jadwal['deskripsi']); ?></span></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Persyaratan Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list fa-fw mr-2 text-green-600"></i>
                Persyaratan
            </h3>
            <ul class="space-y-2 text-sm">
                <?php if(!empty($jadwalTefaFair[0]['persyaratan'])): ?>
                    <?php $__currentLoopData = $jadwalTefaFair[0]['persyaratan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span><?php echo e($req); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Registration History Table -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Daftar Tefa Fair</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar semua pendaftaran Tefa Fair yang pernah Anda ikuti.</p>
            </div>
            <a href="<?php echo e(route('mahasiswa.tefa-fair.create')); ?>" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                <i class="fas fa-plus mr-2"></i>Daftar Tefa Fair
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($loop->iteration); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($item->semester); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo e($item->statusBadge['class'] ?? ''); ?>">
                                    <?php echo e($item->statusBadge['text'] ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-green-600 hover:text-green-900">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum pernah mendaftar Tefa Fair.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/tefa-fair/index.blade.php ENDPATH**/ ?>
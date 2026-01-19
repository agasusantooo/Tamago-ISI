<?php $__env->startSection('title', 'Monitoring Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Monitoring Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-4">Monitoring Perkembangan dan Status Mahasiswa</h3>

        <form action="<?php echo e(route('kaprodi.monitoring')); ?>" method="GET" class="mb-6 flex items-center space-x-4">
            <div class="flex-grow">
                <label for="status" class="block text-sm font-medium text-gray-700">Filter berdasarkan Status</label>
                <select id="status" name="status" class="mt-1 block w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    <option value="">-- Semua Status --</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php echo e($selectedStatus == $status ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst($status)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="pt-6">
                <button type="submit" class="px-5 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Filter</button>
                <a href="<?php echo e(route('kaprodi.monitoring')); ?>" class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">Reset</a>
            </div>
        </form>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <?php
                            $columns = [
                                'nim' => 'NIM',
                                'nama' => 'Nama Mahasiswa',
                                'tahapan_saat_ini' => 'Tahapan Saat Ini',
                                'progress' => 'Progress',
                                'status' => 'Status'
                            ];
                        ?>

                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="<?php echo e(route('kaprodi.monitoring', array_merge(request()->query(), ['sort_by' => $key, 'sort_dir' => ($sortBy == $key && $sortDir == 'asc') ? 'desc' : 'asc']))); ?>" class="flex items-center">
                                    <?php echo e($value); ?>

                                    <?php if($sortBy == $key): ?>
                                        <i class="fas <?php echo e($sortDir == 'asc' ? 'fa-sort-up' : 'fa-sort-down'); ?> ml-2"></i>
                                    <?php else: ?>
                                        <i class="fas fa-sort text-gray-300 ml-2"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $monitoringData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($data->nim); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($data->nama); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($data->tahapan_saat_ini); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo e($data->progress); ?>%"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium"><?php echo e($data->progress); ?>%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if($data->status == 'lulus'): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800"><?php echo e(ucfirst($data->status)); ?></span>
                            <?php elseif($data->status == 'aktif'): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800"><?php echo e(ucfirst($data->status)); ?></span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-800"><?php echo e(ucfirst($data->status)); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="<?php echo e(route('kaprodi.monitoring.show', ['mahasiswa' => $data->nim])); ?>" class="px-3 py-1 text-xs bg-teal-600 text-white rounded hover:bg-teal-700">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data mahasiswa untuk ditampilkan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/kaprodi/monitoring.blade.php ENDPATH**/ ?>
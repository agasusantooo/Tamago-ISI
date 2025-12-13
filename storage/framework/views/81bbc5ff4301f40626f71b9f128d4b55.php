<?php $__env->startSection('title', 'Pengelolaan Dosen Pembimbing'); ?>
<?php $__env->startSection('page-title', 'Pengelolaan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Session Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Pengelolaan Dosen Pembimbing -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-1">Pengelolaan Peran Dosen Pembimbing</h3>
        <p class="text-sm text-gray-600 mb-4">Atur jabatan fungsional untuk menentukan peran dosen sebagai Pembimbing 1 atau Pembimbing 2.</p>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dosen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan Fungsional</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran Maksimal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $peran = 'Belum Ditentukan';
                            if ($dosen->jabatan_fungsional === 'Lektor' || $dosen->jabatan_fungsional === 'Lektor Kepala' || $dosen->jabatan_fungsional === 'Guru Besar') {
                                $peran = 'Pembimbing 1 & 2';
                            } elseif ($dosen->jabatan_fungsional === 'Asisten Ahli') {
                                $peran = 'Pembimbing 2';
                            }
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($dosen->user->name ?? $dosen->nama); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($dosen->nidn); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo e($dosen->jabatan_fungsional ?? 'Belum Diatur'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($peran === 'Pembimbing 1 & 2'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo e($peran); ?>

                                    </span>
                                <?php elseif($peran === 'Pembimbing 2'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <?php echo e($peran); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <?php echo e($peran); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="<?php echo e(route('kaprodi.pengelolaan.dosen.update', $dosen->nidn)); ?>" method="POST" class="flex items-center space-x-2">
                                    <?php echo csrf_field(); ?>
                                    <select name="jabatan_fungsional" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="">Pilih Jabatan</option>
                                        <?php $__currentLoopData = $jabatanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e($dosen->jabatan_fungsional == $key ? 'selected' : ''); ?>>
                                                <?php echo e($value); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="submit" class="px-3 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-xs">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data dosen.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/kaprodi/pengelolaan.blade.php ENDPATH**/ ?>
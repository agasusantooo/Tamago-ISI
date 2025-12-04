<?php $__env->startSection('title', 'Riwayat Pengajuan Proposal - Tamago ISI'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Riwayat Pengajuan Proposal</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar semua proposal yang pernah Anda ajukan.</p>
            </div>
            <a href="<?php echo e(route('mahasiswa.proposal.create')); ?>" class="bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-800 transition">
                <i class="fas fa-plus mr-2"></i>Ajukan Proposal Baru
            </a>
        </div>

        <!-- Alerts -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-green-800"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $proposalHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($loop->iteration); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e(Str::limit($proposal->judul, 50)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($proposal->tanggal_pengajuan ? $proposal->tanggal_pengajuan->format('d M Y') : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php $badge = $proposal->statusBadge; ?>
                                <span class="<?php echo e($badge['class']); ?> px-3 py-1 text-xs font-semibold rounded-full">
                                    <?php echo e($badge['text']); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo e(route('mahasiswa.proposal.show', $proposal->id)); ?>" class="text-yellow-600 hover:text-yellow-900">Detail</a>
                                <?php if($proposal->status == 'revisi'): ?>
                                    <a href="<?php echo e(route('mahasiswa.proposal.edit', $proposal->id)); ?>" class="text-blue-600 hover:text-blue-900 ml-4">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum pernah mengajukan proposal.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/proposal/index.blade.php ENDPATH**/ ?>
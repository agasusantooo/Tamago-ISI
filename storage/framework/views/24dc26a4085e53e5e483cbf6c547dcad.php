<?php $__env->startSection('title', 'Riwayat Pendaftaran Story Conference - Tamago ISI'); ?>
<?php $__env->startSection('page-title', 'Story Conference'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Jadwal Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Story Conference</h2>
            <?php $__currentLoopData = $jadwalStoryConference; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="space-y-3 text-sm">
                    <p class="flex items-center text-gray-700"><i class="fas fa-calendar-alt fa-fw mr-2 text-purple-600"></i><span class="font-semibold mr-1">Tanggal:</span> <?php echo e($jadwal['tanggal']); ?></p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-clock fa-fw mr-2 text-purple-600"></i><span class="font-semibold mr-1">Waktu:</span> <?php echo e($jadwal['waktu']); ?></p>
                    <p class="flex items-center text-gray-700"><i class="fas fa-map-marker-alt fa-fw mr-2 text-purple-600"></i><span class="font-semibold mr-1">Tempat:</span> <?php echo e($jadwal['tempat']); ?></p>
                    <p class="flex items-start text-gray-700"><i class="fas fa-info-circle fa-fw mr-2 mt-1 text-purple-600"></i><span class="font-semibold mr-1">Deskripsi:</span> <span class="flex-1"><?php echo e($jadwal['deskripsi']); ?></span></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Persyaratan Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list fa-fw mr-2 text-purple-600"></i>
                Persyaratan
            </h3>
            <ul class="space-y-2 text-sm">
                <?php if(!empty($jadwalStoryConference[0]['persyaratan'])): ?>
                    <?php $__currentLoopData = $jadwalStoryConference[0]['persyaratan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span class="text-gray-700"><?php echo e($req); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Daftar Story Conference</h3>
                <p class="text-sm text-gray-600 mt-1">Daftar semua story conference yang pernah Anda ikuti.</p>
            </div>
            <a href="<?php echo e(route('mahasiswa.story-conference.create')); ?>" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>Daftar Story Conference
            </a>
        </div>

        <!-- Alerts -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
                <p class="text-green-800"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">Judul Karya</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal Daftar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Slot Waktu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($loop->iteration); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e(Str::limit($item->judul_karya, 40)); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($item->tanggal_daftar ? $item->tanggal_daftar->format('d M Y') : '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($item->slot_waktu); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo e($item->statusBadge['class'] ?? ''); ?>">
                                    <?php echo e($item->statusBadge['text'] ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-purple-600 hover:text-purple-900">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Anda belum pernah mendaftar story conference.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/story-conference/index.blade.php ENDPATH**/ ?>
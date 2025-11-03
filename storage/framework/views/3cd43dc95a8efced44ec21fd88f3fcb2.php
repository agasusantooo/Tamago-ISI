<div class="bg-white border-b shadow-md flex items-center justify-between px-8" style="height: 96.57px;">
    <div>
        <h1 class="text-lg font-semibold text-gray-700"><?php echo $__env->yieldContent('page-title', 'Mahasiswa'); ?></h1>
        <p class="text-xs text-gray-500">Progress Tugas Akhir</p>
    </div>

    <div class="flex items-center space-x-4">
        <div class="flex-1 w-40">
            <div class="flex justify-between items-center mb-1">
                <p class="text-xs font-semibold text-yellow-800">
                    <?php echo e($latestProposal && $latestProposal->status == 'disetujui' ? '100' : '65'); ?>%
                </p>
            </div>
            <div class="w-full bg-gray-100 h-3 rounded-full">
                <div class="bg-yellow-700 h-3 rounded-full transition-all duration-500"
                     style="width: <?php echo e($latestProposal && $latestProposal->status == 'disetujui' ? '100' : '65'); ?>%;"></div>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <span class="text-gray-600 text-sm"><?php echo e(Auth::user()->name); ?></span>
            <img src="<?php echo e(asset('images/avatar-default.png')); ?>" alt="Avatar" class="w-9 h-9 rounded-full">
        </div>
    </div>
</div>
<?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/partials/header-mahasiswa.blade.php ENDPATH**/ ?>
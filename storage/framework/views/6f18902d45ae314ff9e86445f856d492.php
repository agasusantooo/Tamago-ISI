<div class="bg-white border-b border-yellow-100 shadow-md flex items-center px-6 py-4">
    <div class="flex-1 mr-8">
        <h1 class="text-lg font-semibold text-gray-700"><?php echo $__env->yieldContent('page-title', 'Mahasiswa'); ?></h1>
        <div class="flex items-center mt-2 mb-1">
            <p class="text-xs text-gray-500 mr-2">Progress Tugas Akhir</p>
            <div class="flex-1 bg-gray-100 h-3 rounded-full">
                <div class="bg-yellow-700 h-3 rounded-full transition-all duration-500"
                     style="width: <?php echo e($latestProposal && $latestProposal->status == 'disetujui' ? '100' : '65'); ?>%;"></div>
            </div>
            <p class="text-xs font-semibold text-yellow-800 ml-2">
                <?php echo e($latestProposal && $latestProposal->status == 'disetujui' ? '100' : '65'); ?>%
            </p>
        </div>
        <div class="h-3"></div>
    </div>

    <div class="flex flex-col items-center">
        <img src="<?php echo e(asset('images/user.png')); ?>" alt="User Icon" class="w-9 h-9 rounded-full">
        <span class="text-gray-600 text-xs mt-1"><?php echo e(Auth::user()->name); ?></span>
    </div>
</div>
<?php /**PATH C:\laragon\www\tam\ooo\Tamago-ISI-main\resources\views/mahasiswa/partials/header-mahasiswa.blade.php ENDPATH**/ ?>
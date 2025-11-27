<header class="bg-white border-b shadow-md flex items-center px-6 py-4">
    <div class="flex-1 mr-8">
        <h1 class="text-lg font-semibold text-gray-700"><?php echo $__env->yieldContent('page-title', 'Mahasiswa'); ?></h1>
        <div class="flex items-center mt-2 mb-1">
            <p class="text-xs text-gray-500 mr-2">Progress Tugas Akhir</p>
            <?php
                $headerPct = isset($progress) ? (int) max(0, min(100, $progress)) : (isset($latestProposal) && $latestProposal->status == 'disetujui' ? 100 : 0);
            ?>
            <div class="flex-1 bg-gray-100 h-3 rounded-full">
                <div id="headerProgressBarInner" class="bg-yellow-700 h-3 rounded-full transition-all duration-500"
                     style="width: <?php echo e($headerPct); ?>%;"></div>
            </div>
            <p id="headerProgressPct" class="text-xs font-semibold text-yellow-800 ml-2">
                <?php echo e($headerPct); ?>%
            </p>
        </div>
        <div class="h-3"></div>
    </div>

    <div class="flex flex-col items-center">
        <img src="<?php echo e(asset('images/user.png')); ?>" alt="User Icon" class="w-9 h-9 rounded-full">
        <span class="text-gray-600 text-xs mt-1"><?php echo e(Auth::user()->name); ?></span>
    </header>
<?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/partials/header-mahasiswa.blade.php ENDPATH**/ ?>
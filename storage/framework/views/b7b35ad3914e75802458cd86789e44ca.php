<header class="bg-white border-b shadow-md">
    <div class="flex items-center px-6 py-4">
        <div class="flex-1 mr-8">
            <h1 class="text-lg font-semibold text-gray-700">Dosen Penguji</h1>
            <div class="mt-2 mb-1">
                <p class="text-xs text-gray-500">Mahasiswa Aktif: <span class="font-semibold text-purple-600"><?php echo e($mahasiswaAktifCount ?? 0); ?></span> | Tugas Review: <span class="font-semibold text-indigo-600"><?php echo e($tugasReview ?? 0); ?></span></p>
            </div>
            <div class="h-3"></div>
        </div>
    
        <div class="flex flex-col items-center">
            <img src="<?php echo e(asset('images/user.png')); ?>" alt="User Icon" class="w-9 h-9 rounded-full">
            <span class="text-gray-600 text-xs mt-1"><?php echo e(Auth::user()->name); ?></span>
        </div>
    </div>
</header>
<?php /**PATH D:\C\Tamago-ISI\resources\views/dosen_penguji/partials/header-dosen_penguji.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Setup Dosen Seminar'); ?>
<?php $__env->startSection('page-title', 'Setup Dosen Seminar'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">

    <!-- Session Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-1">Pengelolaan Dosen Mata Kuliah Seminar</h3>
        <p class="text-sm text-gray-600 mb-4">Pilih dosen yang akan mengampu mata kuliah Seminar.</p>
        
        <form action="<?php echo e(route('kaprodi.dosen-seminar.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center">
                            <input id="dosen_<?php echo e($dosen->nidn); ?>" name="dosen_nidns[]" type="checkbox" value="<?php echo e($dosen->nidn); ?>"
                                   <?php echo e($dosen->is_dosen_seminar ? 'checked' : ''); ?>

                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="dosen_<?php echo e($dosen->nidn); ?>" class="ml-3 block text-sm font-medium text-gray-700">
                                <?php echo e($dosen->user->name ?? $dosen->nama); ?>

                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="flex justify-end pt-6">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Dosen Seminar</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/kaprodi/dosen-seminar.blade.php ENDPATH**/ ?>
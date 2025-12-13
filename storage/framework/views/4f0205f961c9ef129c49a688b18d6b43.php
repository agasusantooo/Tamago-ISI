<?php $__env->startSection('title', 'Setup Koordinator TEFA'); ?>
<?php $__env->startSection('page-title', 'Setup Koordinator TEFA'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">

    <!-- Session Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-teal-800 mb-1">Pengelolaan Koordinator TEFA Fair</h3>
        <p class="text-sm text-gray-600 mb-4">Pilih satu dosen yang akan menjadi koordinator untuk kegiatan TEFA Fair.</p>
        
        <form action="<?php echo e(route('kaprodi.koordinator-tefa.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Koordinator TEFA</label>
                    <select id="user_id" name="user_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        <option value="">-- Pilih Dosen --</option>
                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dosen->user->id); ?>" 
                                    <?php echo e(optional($currentCoordinator)->id == $dosen->user->id ? 'selected' : ''); ?>>
                                <?php echo e($dosen->user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="flex justify-end pt-6">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Koordinator</button>
            </div>
        </form>

        <?php if($currentCoordinator): ?>
        <div class="mt-8 pt-6 border-t">
            <h4 class="text-md font-semibold text-gray-700">Koordinator TEFA Saat Ini</h4>
            <p class="text-lg text-teal-700 font-bold mt-2"><?php echo e($currentCoordinator->name); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/kaprodi/koordinator-tefa.blade.php ENDPATH**/ ?>
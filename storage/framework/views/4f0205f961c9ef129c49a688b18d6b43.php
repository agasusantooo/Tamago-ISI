<?php $__env->startSection('title', 'Setup Koordinator Koordinator Prodi'); ?>
<?php $__env->startSection('page-title', 'Setup Koordinator'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">

    <!-- Session Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-teal-800 mb-1">Pengelolaan Koordinator TEFA Fair</h3>
                <p class="text-sm text-gray-600 mb-4">Pilih satu dosen yang akan menjadi koordinator untuk kegiatan TEFA Fair.</p>

                <form action="<?php echo e(route('kaprodi.koordinator-tefa.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <div>
                            <label for="user_id_tefa" class="block text-sm font-medium text-gray-700">Koordinator TEFA</label>
                            <select id="user_id_tefa" name="user_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                <option value="">-- Pilih Dosen --</option>
                                <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dosen->user->id); ?>" 
                                            <?php echo e(optional($currentCoordinatorTefa)->id == $dosen->user->id ? 'selected' : ''); ?>>
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

                <?php if($currentCoordinatorTefa): ?>
                <div class="mt-8 pt-6 border-t">
                    <h4 class="text-md font-semibold text-gray-700">Koordinator TEFA Saat Ini</h4>
                    <p class="text-lg text-teal-700 font-bold mt-2"><?php echo e($currentCoordinatorTefa->name); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-indigo-800 mb-1">Pengelolaan Koordinator Story Conference</h3>
                <p class="text-sm text-gray-600 mb-4">Pilih satu dosen yang akan menjadi koordinator untuk kegiatan Story Conference.</p>

                <form action="<?php echo e(route('kaprodi.koordinator-story-conference.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <div>
                            <label for="user_id_story" class="block text-sm font-medium text-gray-700">Koordinator Story Conference</label>
                            <select id="user_id_story" name="user_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Dosen --</option>
                                <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dosen->user->id); ?>" 
                                            <?php echo e(optional($currentCoordinatorStory)->id == $dosen->user->id ? 'selected' : ''); ?>>
                                        <?php echo e($dosen->user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-6">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Simpan Koordinator</button>
                    </div>
                </form>

                <?php if($currentCoordinatorStory): ?>
                <div class="mt-8 pt-6 border-t">
                    <h4 class="text-md font-semibold text-gray-700">Koordinator Story Conference Saat Ini</h4>
                    <p class="text-lg text-indigo-700 font-bold mt-2"><?php echo e($currentCoordinatorStory->name); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/kaprodi/koordinator-tefa.blade.php ENDPATH**/ ?>
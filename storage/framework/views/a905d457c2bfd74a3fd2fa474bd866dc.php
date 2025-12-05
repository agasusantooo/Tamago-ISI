<?php $__env->startSection('title', 'Pengaturan Semester Kegiatan'); ?>
<?php $__env->startSection('page-title', 'Setup Semester Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">

        <!-- Session Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Sukses</p>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error</p>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-teal-800 mb-4">Tentukan Semester untuk Kegiatan Akademik</h3>
            <p class="text-sm text-gray-600 mb-6">Pilih semester di mana kegiatan Seminar, TEFA Fair, dan Tugas Akhir akan dilaksanakan.</p>
            
            <form action="<?php echo e(route('kaprodi.timeline.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <?php $__currentLoopData = $activityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeKey => $typeName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label for="<?php echo e($typeKey); ?>_semester_id" class="block text-sm font-medium text-gray-700">Semester untuk <?php echo e($typeName); ?></label>
                        <select id="<?php echo e($typeKey); ?>_semester_id" name="<?php echo e($typeKey); ?>_semester_id" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            <option value="">-- Tidak Ditentukan --</option>
                            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($semester->id); ?>" 
                                        <?php echo e(optional($activitySemesters->get($typeKey))->semester_id == $semester->id ? 'selected' : ''); ?>>
                                    <?php echo e($semester->nama); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = [$typeKey . '_semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Pengaturan Semester Kegiatan</button>
                </div>
            </form>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/kaprodi/timeline.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Pendaftaran Tefa Fair - Tamago ISI'); ?>
<?php $__env->startSection('page-title', 'Tefa Fair'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <!-- Alerts -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-green-700"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md shadow-sm">
            <p class="text-red-700"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>
     <?php if($errors->any()): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold text-red-800">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside text-red-700 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
        <a href="<?php echo e(route('mahasiswa.tefa-fair.index')); ?>" class="text-gray-600 hover:text-gray-800 text-sm mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Tefa Fair
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Tefa Fair</h2>
        <p class="text-sm text-gray-600 mb-6">Daftarkan karya Anda untuk pameran.</p>

        <?php if(!$projekAkhir): ?>
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <i class="fas fa-lock text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 font-medium mb-2">Anda belum memiliki Proyek Akhir.</p>
                <p class="text-sm text-gray-500">Fitur ini hanya tersedia setelah proyek akhir dimulai.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('mahasiswa.tefa-fair.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-6">
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                    <input type="text" id="semester" name="semester" 
                        value="<?php echo e(old('semester', $tefaFair->semester ?? '')); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                        placeholder="Contoh: Gasal 2024/2025" required>
                </div>
                <div class="mb-6">
                    <label for="daftar_kebutuhan" class="block text-sm font-semibold text-gray-700 mb-2">Daftar Kebutuhan Pameran</label>
                    <textarea id="daftar_kebutuhan" name="daftar_kebutuhan" rows="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                        placeholder="Tuliskan daftar kebutuhan untuk pameran (misal: meja, kursi, listrik, dll)" required><?php echo e(old('daftar_kebutuhan', $tefaFair->daftar_kebutuhan ?? '')); ?></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">File Presentasi/Poster</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition cursor-pointer"
                        onclick="document.getElementById('filePresentasi').click()">
                        <i class="fas fa-file-powerpoint text-4xl text-green-500 mb-3"></i>
                        <p class="text-sm text-gray-600 mb-1">Klik untuk browse</p>
                        <p class="text-xs text-gray-500">PDF/PPT, maksimal 20 MB</p>
                        <input type="file" id="filePresentasi" name="file_presentasi" accept=".pdf,.ppt,.pptx" class="hidden">
                    </div>
                    <?php if(isset($tefaFair) && $tefaFair->file_presentasi): ?>
                        <p class="text-xs text-gray-600 mt-2">File saat ini: <a href="<?php echo e(Storage::url($tefaFair->file_presentasi)); ?>" target="_blank" class="text-blue-600 hover:underline"><?php echo e(basename($tefaFair->file_presentasi)); ?></a></p>
                    <?php endif; ?>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Pendaftaran
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/tefa-fair/create.blade.php ENDPATH**/ ?>
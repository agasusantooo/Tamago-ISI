<?php
$isEdit = isset($proposal) && !empty($proposal->id);
?>



<?php $__env->startSection('title', $isEdit ? 'Edit Revisi Proposal' : 'Pengajuan Proposal Baru'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Alerts -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <p class="text-green-800"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                <p class="text-red-800"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
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

    <!-- Main Grid Layout for Form and Side Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start justify-center">
        <!-- Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 w-full">
                <a href="<?php echo e(route('mahasiswa.proposal.index')); ?>" class="text-gray-600 hover:text-gray-800 text-sm mb-4 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Proposal
                </a>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    <?php echo e($isEdit ? 'Edit Revisi Proposal' : 'Form Pengajuan Proposal'); ?>

                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    <?php echo e($isEdit ? 'Perbarui informasi proposal Anda sesuai feedback dari dosen.' : 'Lengkapi semua informasi yang diperlukan untuk mengajukan proposal tugas akhir.'); ?>

                </p>

                <form id="proposalForm" method="POST" 
                      action="<?php echo e($isEdit ? route('mahasiswa.proposal.update', $proposal->id) : route('mahasiswa.proposal.store')); ?>" 
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php if($isEdit): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Tugas Akhir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" 
                            value="<?php echo e(old('judul', $proposal->judul ?? '')); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent"
                            placeholder="Masukkan Judul Tugas Akhir" required>
                    </div>
                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi Singkat/Abstrak <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent"
                            placeholder="Tuliskan Deskripsi Singkat atau Abstrak Proposal Anda" required><?php echo e(old('deskripsi', $proposal->deskripsi ?? '')); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 100 karakter</p>
                    </div>
                    <!-- Rumpun Ilmu -->
                    <div class="mb-6">
                        <label for="rumpun_ilmu" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rumpun Ilmu <span class="text-red-500">*</span>
                        </label>
                        <select id="rumpun_ilmu" name="rumpun_ilmu"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent" required>
                            <option value="">-- Pilih Rumpun Ilmu --</option>
                            <?php
                                $selectedRumpun = old('rumpun_ilmu', $proposal->rumpun_ilmu ?? '');
                            ?>
                            <option value="Penciptaan Seni" <?php echo e($selectedRumpun == 'Penciptaan Seni' ? 'selected' : ''); ?>>Penciptaan Seni</option>
                            <option value="Pengkajian Seni" <?php echo e($selectedRumpun == 'Pengkajian Seni' ? 'selected' : ''); ?>>Pengkajian Seni</option>
                            <option value="Media Rekam" <?php echo e($selectedRumpun == 'Media Rekam' ? 'selected' : ''); ?>>Media Rekam</option>
                        </select>
                    </div>
                    <!-- Dosen Pembimbing -->
                    <div class="mb-6">
                        <label for="dosen" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pilih Dosen Pembimbing
                        </label>
                        <select id="dosen" name="dosen_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-600 focus:border-transparent">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            <?php
                                $selectedDosen = old('dosen_id', $proposal->dosen_id ?? '');
                            ?>
                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dosen->id); ?>" <?php echo e($selectedDosen == $dosen->id ? 'selected' : ''); ?>>
                                    <?php echo e($dosen->nama); ?> - <?php echo e($dosen->gelar); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <!-- File Proposal -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            File Proposal (PDF) <span class="text-red-500"><?php echo e($isEdit ? '' : '*'); ?></span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-500 transition cursor-pointer"
                            onclick="document.getElementById('fileProposal').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                            <p class="text-xs text-gray-500">PDF, maksimal 10 MB. <?php echo e($isEdit ? 'Kosongkan jika tidak ingin mengubah file.' : ''); ?></p>
                            <input type="file" id="fileProposal" name="file_proposal" accept=".pdf" class="hidden">
                            <p id="proposalFileName" class="text-sm text-yellow-700 font-medium mt-2"></p>
                        </div>
                         <?php if($isEdit && !empty($proposal->file_proposal)): ?>
                            <p class="text-xs text-gray-600 mt-2">File saat ini: <a href="<?php echo e(Storage::url($proposal->file_proposal)); ?>" target="_blank" class="text-blue-600 hover:underline"><?php echo e(basename($proposal->file_proposal)); ?></a></p>
                        <?php endif; ?>
                    </div>
                    <!-- File Pitch Deck -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            File Pitch Deck (PDF/PPT)
                        </label>
                         <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-500 transition cursor-pointer"
                            onclick="document.getElementById('filePitchDeck').click()">
                            <i class="fas fa-file-powerpoint text-4xl text-yellow-600 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                            <p class="text-xs text-gray-500">PDF/PPT, maksimal 15 MB. <?php echo e($isEdit ? 'Kosongkan jika tidak ingin mengubah file.' : ''); ?></p>
                            <input type="file" id="filePitchDeck" name="file_pitch_deck" accept=".pdf,.ppt,.pptx" class="hidden">
                            <p id="pitchDeckFileName" class="text-sm text-yellow-700 font-medium mt-2"></p>
                        </div>
                         <?php if($isEdit && !empty($proposal->file_pitch_deck)): ?>
                            <p class="text-xs text-gray-600 mt-2">File saat ini: <a href="<?php echo e(Storage::url($proposal->file_pitch_deck)); ?>" target="_blank" class="text-blue-600 hover:underline"><?php echo e(basename($proposal->file_pitch_deck)); ?></a></p>
                        <?php endif; ?>
                    </div>
                    <!-- Tombol -->
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-800 transition">
                            <?php if($isEdit): ?>
                                <i class="fas fa-sync-alt mr-2"></i>Update Revisi
                            <?php else: ?>
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Proposal
                            <?php endif; ?>
                        </button>
                        <?php if(!$isEdit): ?>
                        <button type="button" onclick="saveDraft()"
                            class="px-6 py-3 border-2 border-yellow-700 text-yellow-700 rounded-lg font-semibold hover:bg-yellow-50 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Draft
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <!-- Download Template Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Template Laporan</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Unduh template laporan proposal terbaru untuk memastikan format yang benar.
                </p>
                <a href="#" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i> Unduh Template
                </a>
            </div>
        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/proposal/create.blade.php ENDPATH**/ ?>
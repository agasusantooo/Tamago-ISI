<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Pengajuan Proposal - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php echo $__env->make('partials.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header Putih dengan Progress -->
            <div class="bg-white shadow p-5 border-b">
                <div class="flex items-center space-x-5">
                    <!-- Logo -->
                    <img src="/images/logo-isi.png" alt="Logo ISI" class="w-14 h-14" onerror="this.style.display='none'">

                    <!-- Info Tamago -->
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Tamago ISI</h2>
                                <p class="text-sm text-gray-600">Sistem TA</p>
                            </div>
                            <p class="text-xs text-gray-700 font-semibold">
                                <?php echo e($latestProposal && $latestProposal->status == 'disetujui' ? '100' : '65'); ?>%
                            </p>
                        </div>

                        <p class="text-xs text-gray-500 mb-1">Progress Tugas Akhir</p>
                        <div class="w-full bg-gray-100 h-3 rounded-full">
                            <div class="bg-yellow-700 h-3 rounded-full transition-all duration-500"
                                style="width: <?php echo e($latestProposal && $latestProposal->status == 'disetujui' ? '100' : '65'); ?>%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">

                    <!-- Alert Messages -->
                    <?php if(session('success')): ?>
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <p class="text-green-700"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <p class="text-red-700"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
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

                    <!-- Form dan Sidebar Status -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Form (kiri) -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Form Pengajuan Proposal</h3>
                                <p class="text-sm text-gray-600 mb-6">
                                    Lengkapi semua informasi yang diperlukan untuk mengajukan proposal tugas akhir.
                                </p>

                                <form id="proposalForm" method="POST" action="<?php echo e(route('mahasiswa.proposal.submit')); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>

                                    <!-- Judul -->
                                    <div class="mb-6">
                                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Judul Tugas Akhir <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="judul" name="judul" 
                                            value="<?php echo e(old('judul')); ?>"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Masukkan Judul Tugas Akhir" required>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="mb-6">
                                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Deskripsi Singkat/Abstrak <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="deskripsi" name="deskripsi" rows="6"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Tuliskan Deskripsi Singkat atau Abstrak Proposal Anda" required><?php echo e(old('deskripsi')); ?></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Minimum 100 karakter</p>
                                    </div>

                                    <!-- Dosen Pembimbing -->
                                    <div class="mb-6">
                                        <label for="dosen" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Pilih Dosen Pembimbing
                                        </label>
                                        <select id="dosen" name="dosen_id"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">-- Pilih Dosen Pembimbing --</option>
                                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dosen->id); ?>" <?php echo e(old('dosen_id') == $dosen->id ? 'selected' : ''); ?>>
                                                    <?php echo e($dosen->nama); ?> - <?php echo e($dosen->gelar); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <!-- File Proposal -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            File Proposal (PDF) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer"
                                            onclick="document.getElementById('fileProposal').click()">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                                            <p class="text-xs text-gray-500">PDF, maksimal 10 MB</p>
                                            <input type="file" id="fileProposal" name="file_proposal" accept=".pdf" class="hidden">
                                            <p id="proposalFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                        </div>
                                    </div>

                                    <!-- File Pitch Deck -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            File Pitch Deck (PDF/PPT)
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer"
                                            onclick="document.getElementById('filePitchDeck').click()">
                                            <i class="fas fa-file-powerpoint text-4xl text-gray-400 mb-3"></i>
                                            <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                                            <p class="text-xs text-gray-500">PDF/PPT, maksimal 15 MB</p>
                                            <input type="file" id="filePitchDeck" name="file_pitch_deck" accept=".pdf,.ppt,.pptx" class="hidden">
                                            <p id="pitchDeckFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                        </div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="flex space-x-4">
                                        <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                            <i class="fas fa-paper-plane mr-2"></i>Ajukan Proposal
                                        </button>
                                        <button type="button" onclick="saveDraft()"
                                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                                            <i class="fas fa-save mr-2"></i>Simpan Draft
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Status Pengajuan -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-4">Status Pengajuan</h3>
                                <?php if($latestProposal): ?>
                                    <?php $badge = $latestProposal->statusBadge; ?>
                                    <span class="px-3 py-1 bg-<?php echo e($badge['color']); ?>-100 text-<?php echo e($badge['color']); ?>-800 text-sm font-semibold rounded-full">
                                        <?php echo e($badge['text']); ?>

                                    </span>
                                <?php else: ?>
                                    <p class="text-sm text-gray-600">Belum ada pengajuan proposal.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function saveDraft() {
            const form = document.getElementById('proposalForm');
            const formData = new FormData(form);

            fetch("<?php echo e(route('mahasiswa.proposal.draft')); ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                }
            })
            .then(res => res.json())
            .then(data => alert(data.success ? data.message : 'Gagal menyimpan draft'))
            .catch(() => alert('Terjadi kesalahan saat menyimpan draft'));
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\Tamago-ISI-main\Tamago-ISI-main\resources\views/mahasiswa/proposal.blade.php ENDPATH**/ ?>
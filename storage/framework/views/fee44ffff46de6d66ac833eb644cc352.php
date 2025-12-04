<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Story Conference - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php echo $__env->make('mahasiswa.partials.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <?php echo $__env->make('mahasiswa.partials.header-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content (2/3) -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Jadwal Story Conference -->
                            <div class="bg-white rounded-lg shadow-sm">
                                <div class="p-6 border-b">
                                    <h2 class="text-xl font-bold text-gray-800">Jadwal Story Conference</h2>
                                    <p class="text-sm text-gray-600 mt-1">Jadwal acara diskusi dan review untuk proyek tugas akhir.</p>
                                </div>

                                <div class="p-6 space-y-4">
                                    <?php $__currentLoopData = $jadwalStoryConference; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="<?php echo e($jadwal['bg_color']); ?> border-l-4 <?php echo e($jadwal['border_color']); ?> rounded-lg p-6">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                        <i class="fas fa-presentation-screen text-2xl <?php echo e(str_replace('bg-', 'text-', $jadwal['bg_color'])); ?>"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-lg text-gray-800 mb-2"><?php echo e($jadwal['jenis']); ?></h3>
                                                    <div class="grid grid-cols-2 gap-4 mb-3">
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-700">
                                                                <i class="fas fa-calendar mr-2"></i><?php echo e($jadwal['tanggal']); ?>

                                                            </p>
                                                            <p class="text-sm text-gray-600 mt-1">
                                                                <i class="fas fa-clock mr-2"></i><?php echo e($jadwal['waktu']); ?>

                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-700">
                                                                <i class="fas fa-map-marker-alt mr-2"></i><?php echo e($jadwal['tempat']); ?>

                                                            </p>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mb-3"><?php echo e($jadwal['deskripsi']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Persyaratan Pendaftaran -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                                    Persyaratan Pendaftaran:
                                </h3>
                                <ul class="space-y-2 text-sm">
                                    <?php if(!empty($jadwalStoryConference[0]['persyaratan'])): ?>
                                        <?php $__currentLoopData = $jadwalStoryConference[0]['persyaratan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                                <span class="text-gray-700"><?php echo e($req); ?></span>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Sidebar (1/3) -->
                        <div class="space-y-6">
                            <!-- Status Pendaftaran -->
                            <?php if($storyConference): ?>
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                                        Status Pendaftaran
                                    </h3>
                                    
                                    <div class="mb-4">
                                        <span class="px-3 py-1 <?php echo e($storyConference->statusColor); ?> text-sm font-semibold rounded-full">
                                            <?php echo e($storyConference->statusBadge['text']); ?>

                                        </span>
                                        <p class="text-xs text-gray-500 mt-2">Status Saat Ini</p>
                                    </div>

                                    <div class="space-y-3 text-sm">
                                        <div>
                                            <p class="font-semibold text-gray-700">Judul Karya:</p>
                                            <p class="text-gray-600"><?php echo e($storyConference->judul_karya); ?></p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-700">Slot Waktu:</p>
                                            <p class="text-gray-600"><?php echo e($storyConference->slot_waktu); ?></p>
                                        </div>
                                        <?php
                                            // Only build the download URL when we have a valid storyConference with an id
                                            $downloadUrl = null;
                                            if (!empty($storyConference) && !empty($storyConference->id) && !empty($storyConference->file_presentasi)) {
                                                $downloadUrl = route('mahasiswa.story-conference.download', $storyConference->id);
                                            }
                                        ?>
                                        <?php if($downloadUrl): ?>
                                            <div>
                                                <a href="<?php echo e($downloadUrl); ?>" 
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-download mr-2"></i>
                                                    Download File Presentasi
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Timeline Review -->
                                    <div class="mt-6 pt-6 border-t">
                                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Timeline Review</h4>
                                        <div class="space-y-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">Pendaftaran Diterima</p>
                                                    <p class="text-xs text-gray-500"><?php echo e($storyConference->tanggal_daftar->format('d M Y, H:i')); ?></p>
                                                </div>
                                            </div>

                                            <?php if($storyConference->status == 'sedang_direview' || $storyConference->status == 'diterima'): ?>
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-eye text-blue-600 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800">Sedang Direview</p>
                                                        <p class="text-xs text-gray-500">Estimasi: 1-3 hari kerja</p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($storyConference->status == 'diterima' || $storyConference->status == 'konfirmasi_akhir'): ?>
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-clipboard-check text-purple-600 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800">Konfirmasi Akhir</p>
                                                        <p class="text-xs text-gray-500">Menunggu</p>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-circle text-gray-400 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-500">Konfirmasi Akhir</p>
                                                        <p class="text-xs text-gray-400">Menunggu</p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php
                                        // Only build cancel URL when we have a valid storyConference with id and correct status
                                        $cancelUrl = null;
                                        if (!empty($storyConference) && !empty($storyConference->id) && $storyConference->status == 'menunggu_persetujuan') {
                                            $cancelUrl = route('mahasiswa.story-conference.cancel', $storyConference->id);
                                        }
                                    ?>
                                    <?php if($cancelUrl): ?>
                                        <div class="mt-6">
                                            <form method="POST" action="<?php echo e($cancelUrl); ?>" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="w-full px-4 py-2 border-2 border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition text-sm font-medium">
                                                    <i class="fas fa-times mr-2"></i>Batalkan Pendaftaran
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <!-- Form Pendaftaran -->
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                                        Form Pendaftaran
                                    </h3>

                                    <form method="POST" action="<?php echo e(route('mahasiswa.story-conference.store')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>

                                        <!-- Judul Karya -->
                                        <div class="mb-4">
                                            <label for="judul_karya" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Judul Karya yang akan Dipresentasikan <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="judul_karya" name="judul_karya" 
                                                value="<?php echo e(old('judul_karya', $proposal->judul ?? '')); ?>"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                placeholder="Masukkan judul karya"
                                                required>
                                        </div>

                                        <!-- Hidden Slot Waktu -->
                                        <?php if(!empty($jadwalStoryConference[0])): ?>
                                            <input type="hidden" name="slot_waktu" value="<?php echo e($jadwalStoryConference[0]['tanggal'] . ' - ' . $jadwalStoryConference[0]['waktu']); ?>">
                                        <?php endif; ?>

                                        <!-- Upload Materi Presentasi -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Upload Materi Presentasi <span class="text-red-500">*</span>
                                            </label>
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition cursor-pointer" 
                                                onclick="document.getElementById('filePresentasi').click()">
                                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                                <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk browse</p>
                                                <p class="text-xs text-gray-500">Format: PDF, PPT, PPTX (Max: 10MB)</p>
                                                <input type="file" id="filePresentasi" name="file_presentasi" 
                                                    accept=".pdf,.ppt,.pptx" 
                                                    class="hidden" 
                                                    onchange="updateFileName(this, 'fileNameDisplay')"
                                                    required>
                                                <p id="fileNameDisplay" class="text-sm text-blue-600 font-medium mt-2"></p>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" 
                                            class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                            <i class="fas fa-paper-plane mr-2"></i>Daftar Sekarang
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function updateFileName(input, displayId) {
            const file = input.files[0];
            const display = document.getElementById(displayId);
            if (file) {
                display.textContent = `✓ ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            }
        }

        // Drag & Drop
        const dropZone = document.getElementById('filePresentasi')?.parentElement;
        
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('border-blue-500', 'bg-blue-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                document.getElementById('filePresentasi').files = files;
                updateFileName(document.getElementById('filePresentasi'), 'fileNameDisplay');
            }, false);
        }
    </script>
</body>
</html>
<?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/story-conference/create.blade.php ENDPATH**/ ?>
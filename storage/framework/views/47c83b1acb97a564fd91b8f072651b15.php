<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Bimbingan - Tamago ISI</title>
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
                            <!-- Riwayat Bimbingan -->
                            <div class="bg-white rounded-lg shadow-sm">
                                <div class="p-6 border-b">
                                    <h2 class="text-xl font-bold text-gray-800">Riwayat Bimbingan</h2>
                                    <p class="text-sm text-gray-600 mt-1">Daftar semua sesi bimbingan yang telah dilakukan</p>
                                </div>

                                <div class="divide-y">
                                    <?php $__empty_1 = true; $__currentLoopData = $bimbinganList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="p-6 hover:bg-gray-50 transition" data-bimbingan-id="<?php echo e($bimbingan->id_bimbingan); ?>" data-current-status="<?php echo e($bimbingan->status); ?>">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <h3 class="font-bold text-lg text-gray-800"><?php echo e($bimbingan->topik); ?></h3>
                                                        <span class="px-3 py-1 <?php echo e($bimbingan->statusColor); ?> text-xs font-semibold rounded-full">
                                                            <?php echo e($bimbingan->statusBadge['text']); ?>

                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                        <span>
                                                            <i class="fas fa-calendar mr-2"></i>
                                                            <?php echo e($bimbingan->tanggal->format('d M Y')); ?>

                                                        </span>
                                                        <?php if($bimbingan->waktu_mulai): ?>
                                                            <span>
                                                                <i class="fas fa-clock mr-2"></i>
                                                                <?php echo e(\Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i')); ?> WIB
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if($bimbingan->ruang): ?>
                                                            <span>
                                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                                <?php echo e($bimbingan->ruang); ?>

                                                            </span>
                                                        <?php endif; ?>>
                                                    </div>
                                                </div>
                                                <button onclick="toggleDetail(<?php echo e($bimbingan->id_bimbingan); ?>)" 
                                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                    Lihat Detail
                                                </button>
                                            </div>

                                            <!-- Detail Section (Hidden by default) -->
                                            <div id="detail-<?php echo e($bimbingan->id_bimbingan); ?>" class="hidden mt-4 pt-4 border-t">
                                                <div class="space-y-3">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-700 mb-1">Catatan Mahasiswa:</p>
                                                        <p class="text-sm text-gray-600"><?php echo e($bimbingan->catatan_mahasiswa); ?></p>
                                                    </div>
                                                    
                                                    <?php if($bimbingan->catatan_dosen): ?>
                                                        <div class="bg-blue-50 p-4 rounded-lg">
                                                            <p class="text-sm font-semibold text-blue-900 mb-1">
                                                                <i class="fas fa-user-tie mr-2"></i>Feedback Dosen:
                                                            </p>
                                                            <p class="text-sm text-blue-800"><?php echo e($bimbingan->catatan_dosen); ?></p>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($bimbingan->file_pendukung): ?>
                                                        <div>
                                                            <a href="<?php echo e(route('mahasiswa.bimbingan.download', $bimbingan->id_bimbingan)); ?>" 
                                                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                                <i class="fas fa-download mr-2"></i>
                                                                Download File Pendukung
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="p-12 text-center">
                                            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500">Belum ada riwayat bimbingan</p>
                                            <p class="text-sm text-gray-400 mt-2">Ajukan bimbingan baru untuk memulai</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar (1/3) -->
                        <div class="space-y-6">
                            <!-- Jadwal Terjadwal -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                    Jadwal Bimbingan
                                </h3>
                                
                                <div class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $jadwalTerjadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                            <div class="flex items-start justify-between mb-2">
                                                <p class="font-semibold text-sm text-gray-800"><?php echo e($jadwal->topik); ?></p>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                                    <?php echo e($jadwal->statusBadge['text']); ?>

                                                </span>
                                            </div>
                                            <div class="space-y-1 text-xs text-gray-600">
                                                <p>
                                                    <i class="fas fa-calendar w-4"></i>
                                                    <?php echo e($jadwal->tanggal->format('d M Y')); ?>

                                                </p>
                                                <?php if($jadwal->waktu_mulai): ?>
                                                    <p>
                                                        <i class="fas fa-clock w-4"></i>
                                                        <?php echo e(\Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')); ?> WIB
                                                    </p>
                                                <?php endif; ?>
                                                <?php if($jadwal->ruang): ?>
                                                    <p>
                                                        <i class="fas fa-map-marker-alt w-4"></i>
                                                        <?php echo e($jadwal->ruang); ?>

                                                    </p>
                                                <?php endif; ?>
                                                <?php if($jadwal->dosen): ?>
                                                    <p>
                                                        <i class="fas fa-user-tie w-4"></i>
                                                        <?php echo e($jadwal->dosen->nama); ?>

                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="text-center py-6">
                                            <i class="fas fa-calendar-times text-3xl text-gray-300 mb-2"></i>
                                            <p class="text-sm text-gray-500">Tidak ada jadwal bimbingan</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Form Ajukan Bimbingan Baru -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                    Ajukan Bimbingan Baru
                                </h3>

                                <form method="POST" action="<?php echo e(route('mahasiswa.bimbingan.store')); ?>" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>

                                    <!-- Topik Bimbingan -->
                                    <div class="mb-4">
                                        <label for="topik" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Topik Bimbingan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="topik" name="topik" 
                                            value="<?php echo e(old('topik')); ?>"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Contoh: Proposal BAB 1"
                                            required>
                                    </div>

                                    <!-- Catatan -->
                                    <div class="mb-4">
                                        <label for="catatan_mahasiswa" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Catatan atau Pertanyaan untuk Dosen <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="catatan_mahasiswa" name="catatan_mahasiswa" rows="4"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Jelaskan hal yang ingin dibimbingkan..."
                                            required><?php echo e(old('catatan_mahasiswa')); ?></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Minimum 20 karakter</p>
                                    </div>

                                    <!-- Tanggal Pilihan Mahasiswa -->
                                    <div class="mb-4">
                                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Bimbingan (pilih tanggal preferensi) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <input type="date" id="tanggal" name="tanggal"
                                                value="<?php echo e(old('tanggal', \Carbon\Carbon::today()->toDateString())); ?>"
                                                min="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                required />

                                            <input type="time" id="waktu_mulai" name="waktu_mulai"
                                                value="<?php echo e(old('waktu_mulai', '09:00')); ?>"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                                required />
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Pilih tanggal dan jam preferensi Anda untuk sesi bimbingan. Dosen akan mengonfirmasi ketersediaan.</p>
                                    </div>

                                    <!-- File Pendukung -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            File Pendukung (Optional)
                                        </label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition cursor-pointer" 
                                            onclick="document.getElementById('filePendukung').click()">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-xs text-gray-600 mb-1">Klik atau drag & drop file</p>
                                            <p class="text-xs text-gray-500">PDF, DOC, JPG, PNG (Max 5 MB)</p>
                                            <input type="file" id="filePendukung" name="file_pendukung" 
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                                                class="hidden" 
                                                onchange="updateFileName(this, 'fileNameDisplay')">
                                            <p id="fileNameDisplay" class="text-xs text-blue-600 font-medium mt-2"></p>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" 
                                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Bimbingan
                                    </button>
                                </form>
                            </div>
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

        function toggleDetail(id) {
            const detail = document.getElementById('detail-' + id);
            if (detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
            } else {
                detail.classList.add('hidden');
            }
        }

        // Drag & Drop
        const dropZone = document.getElementById('filePendukung').parentElement;
        
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
            document.getElementById('filePendukung').files = files;
            updateFileName(document.getElementById('filePendukung'), 'fileNameDisplay');
        }, false);

        // ============================================================================
        // Auto-refresh untuk deteksi update dari dospem (ACC/Reject bimbingan)
        // ============================================================================
        let lastUpdateTime = null;

        function checkForBimbinganUpdates() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('<?php echo e(route("mahasiswa.bimbingan.check-updates")); ?>', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.bimbingan && data.bimbingan.length > 0) {
                    // Check if any bimbingan status has changed
                    let hasUpdates = false;
                    
                    data.bimbingan.forEach(bimbingan => {
                        const statusElement = document.querySelector(`[data-bimbingan-id="${bimbingan.id}"]`);
                        if (statusElement) {
                            const currentStatus = statusElement.getAttribute('data-current-status');
                            if (currentStatus !== bimbingan.status) {
                                hasUpdates = true;
                            }
                        }
                    });

                    // If there are updates, show notification and reload after a delay
                    if (hasUpdates) {
                        showUpdateNotification();
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                }
            })
            .catch(err => {
                console.warn('Update check failed:', err);
                // Silently fail, don't bother user
            });
        }

        function showUpdateNotification() {
            // Create notification if not exists
            let notification = document.getElementById('update-notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'update-notification';
                notification.className = 'fixed top-4 right-4 bg-green-50 border-l-4 border-green-400 p-4 shadow-lg rounded z-50 animate-slide-in';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-sync-alt text-green-600 mr-3 animate-spin"></i>
                        <div>
                            <p class="text-green-700 font-medium">Ada pembaruan dari dospem!</p>
                            <p class="text-sm text-green-600">Halaman akan dimuat ulang...</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
            }
            notification.style.display = 'block';
        }

        // Start polling for updates every 10 seconds
        setInterval(checkForBimbinganUpdates, 10000);
        // Check immediately on page load
        checkForBimbinganUpdates();

        // Add CSS animation for notification
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            .animate-slide-in {
                animation: slideIn 0.3s ease-out;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 2s linear infinite;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
<?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/bimbingan.blade.php ENDPATH**/ ?>
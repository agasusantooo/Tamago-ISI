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
<body class="bg-gray-50 flex flex-col h-screen">
    <div class="flex-1 flex flex-col lg:flex-row">
        <!-- Sidebar -->
        <?php echo $__env->make('mahasiswa.partials.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <?php echo $__env->make('mahasiswa.partials.header-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col overflow-y-auto">
                <div class="flex flex-col flex-grow p-6"> <!-- Added p-6 here, removed max-w-7xl mx-auto from alerts directly below -->
                    <!-- Alert Messages -->
                    <?php if(session('success')): ?>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 w-full"> <!-- Removed max-w-7xl mx-auto -->
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-yellow-600 mr-3"></i>
                                <p class="text-yellow-700"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 w-full"> <!-- Removed max-w-7xl mx-auto -->
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <p class="text-red-700"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 w-full"> <!-- Removed max-w-7xl mx-auto -->
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

                    <div class="lg:flex lg:gap-6 flex-grow h-full">
                        <!-- Main Content (2/3) -->
                        <div class="lg:w-2/3 h-full">
                             <!-- Form Ajukan Bimbingan Baru -->
                            <div class="bg-white rounded-lg shadow-sm p-6 flex flex-col h-full">
                                <h3 class="font-bold text-xl text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-plus-circle text-yellow-600 mr-2"></i>
                                    Ajukan Bimbingan Baru
                                </h3>
                                <form method="POST" action="<?php echo e(route('mahasiswa.bimbingan.store')); ?>" enctype="multipart/form-data" class="flex flex-col flex-grow">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex-grow">
                                        <div class="grid grid-cols-1 gap-6">
                                            <div>
                                                <div class="mb-4">
                                                    <label for="topik" class="block text-sm font-semibold text-gray-700 mb-2">Topik Bimbingan <span class="text-red-500">*</span></label>
                                                    <input type="text" id="topik" name="topik" value="<?php echo e(old('topik')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm" placeholder="Contoh: Proposal BAB 1" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="catatan_mahasiswa" class="block text-sm font-semibold text-gray-700 mb-2">Catatan atau Pertanyaan <span class="text-red-500">*</span></label>
                                                    <textarea id="catatan_mahasiswa" name="catatan_mahasiswa" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm" placeholder="Jelaskan hal yang ingin dibimbingkan..." required><?php echo e(old('catatan_mahasiswa')); ?></textarea>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">File Pendukung (Optional)</label>
                                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-400 transition cursor-pointer" onclick="document.getElementById('filePendukung').click()">
                                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                                        <p class="text-sm text-gray-600">Klik atau drag & drop</p>
                                                        <input type="file" id="filePendukung" name="file_pendukung" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" onchange="updateFileName(this, 'fileNameDisplay')">
                                                        <p id="fileNameDisplay" class="text-sm text-yellow-600 font-medium mt-2"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-4">
                                                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bimbingan <span class="text-red-500">*</span></label>
                                                    <div class="flex gap-3">
                                                        <input type="date" id="tanggal" name="tanggal" value="<?php echo e(old('tanggal', \Carbon\Carbon::today()->toDateString())); ?>" min="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm" required />
                                                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="<?php echo e(old('waktu_mulai', '09:00')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-auto pt-4">
                                        <button type="submit" class="w-full bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition"><i class="fas fa-paper-plane mr-2"></i>Ajukan Bimbingan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Sidebar (1/3) -->
                        <div class="lg:w-1/3 flex flex-col gap-6 h-full">
                            <!-- Jadwal Terjadwal -->
                            <div class="bg-white rounded-lg shadow-sm p-6 flex-1 overflow-y-auto">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-calendar-check text-yellow-600 mr-2"></i>
                                    Jadwal Bimbingan
                                </h3>
                                <div class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $jadwalTerjadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                                            <div class="flex items-start justify-between mb-2">
                                                <p class="font-semibold text-sm text-gray-800"><?php echo e($jadwal->topik); ?></p>
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded"><?php echo e($jadwal->statusBadge['text']); ?></span>
                                            </div>
                                            <div class="space-y-1 text-xs text-gray-600">
                                                <p><i class="fas fa-calendar w-4"></i> <?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y')); ?></p>
                                                <?php if($jadwal->waktu_mulai): ?><p><i class="fas fa-clock w-4"></i> <?php echo e(\Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')); ?> WIB</p><?php endif; ?>
                                                <?php if($jadwal->ruang): ?><p><i class="fas fa-map-marker-alt w-4"></i> <?php echo e($jadwal->ruang); ?></p><?php endif; ?>
                                                <?php if($jadwal->dosen): ?><p><i class="fas fa-user-tie w-4"></i> <?php echo e($jadwal->dosen->nama); ?></p><?php endif; ?>
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
                            
                            <!-- Riwayat Bimbingan -->
                            <div class="bg-white rounded-lg shadow-sm flex flex-col flex-1 overflow-y-auto">
                                <div class="p-6 border-b">
                                    <h2 class="text-xl font-bold text-gray-800">Riwayat Bimbingan</h2>
                                </div>
                                <div id="bimbinganListContainer" class="divide-y flex-grow overflow-y-auto">
                                    <?php $__empty_1 = true; $__currentLoopData = $bimbinganList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="p-4 hover:bg-gray-50 transition" data-bimbingan-id="<?php echo e($bimbingan->id_bimbingan); ?>" data-current-status="<?php echo e($bimbingan->status); ?>">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-800"><?php echo e($bimbingan->topik); ?></h3>
                                                    <div class="flex items-center space-x-3 text-xs text-gray-600 mt-1">
                                                        <span><i class="fas fa-calendar mr-1"></i><?php echo e(\Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y')); ?></span>
                                                        <span class="px-2 py-0.5 <?php echo e($bimbingan->statusColor); ?> text-xs font-semibold rounded-full"><?php echo e($bimbingan->statusBadge['text']); ?></span>
                                                    </div>
                                                </div>
                                                <button onclick="toggleDetail(<?php echo e($bimbingan->id_bimbingan); ?>)" class="text-yellow-600 hover:text-yellow-800 font-medium text-xs">Detail</button>
                                            </div>
                                            <div id="detail-<?php echo e($bimbingan->id_bimbingan); ?>" class="hidden mt-3 pt-3 border-t text-sm space-y-2">
                                                <div>
                                                    <p class="font-semibold text-gray-700">Catatan:</p>
                                                    <p class="text-gray-600"><?php echo e($bimbingan->catatan_mahasiswa); ?></p>
                                                </div>
                                                <?php if($bimbingan->catatan_dosen): ?>
                                                    <div class="bg-yellow-50 p-3 rounded-lg">
                                                        <p class="font-semibold text-yellow-900">Feedback Dosen:</p>
                                                        <p class="text-yellow-800"><?php echo e($bimbingan->catatan_dosen); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="p-8 text-center">
                                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                            <p class="text-sm text-gray-500">Belum ada riwayat bimbingan</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
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
    </script>
    <script>
        async function fetchBimbinganUpdates() {
            try {
                const res = await fetch("<?php echo e(route('mahasiswa.bimbingan.check-updates')); ?>", { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const json = await res.json();
                if (!json.bimbingan) return;
                const container = document.getElementById('bimbinganListContainer');
                if (!container) return;
                container.innerHTML = '';
                json.bimbingan.forEach(function(b) {
                    const el = document.createElement('div');
                    el.className = 'p-4 hover:bg-gray-50 transition';
                    el.innerHTML = `
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">${b.topik}</h3>
                                <div class="flex items-center space-x-3 text-xs text-gray-600 mt-1">
                                    <span><i class="fas fa-calendar mr-1"></i>${b.tanggal ?? '-'}</span>
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">${b.status}</span>
                                </div>
                            </div>
                            <button onclick="toggleDetail(${b.id})" class="text-yellow-600 hover:text-yellow-800 font-medium text-xs">Detail</button>
                        </div>
                        <div id="detail-${b.id}" class="hidden mt-3 pt-3 border-t text-sm space-y-2">
                            <div>
                                <p class="font-semibold text-gray-700">Catatan:</p>
                                <p class="text-gray-600">${b.catatan_mahasiswa || '-'}</p>
                            </div>
                        </div>
                    `;
                    container.appendChild(el);
                });
            } catch (e) { console.error('Failed to fetch bimbingan updates', e); }
        }
        document.addEventListener('DOMContentLoaded', function() {
            fetchBimbinganUpdates();
            setInterval(fetchBimbinganUpdates, 15000);
        });
    </script>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/bimbingan.blade.php ENDPATH**/ ?>
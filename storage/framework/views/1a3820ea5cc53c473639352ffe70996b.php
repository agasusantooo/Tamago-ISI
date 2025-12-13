<?php $__env->startSection('title', 'Dashboard Dosen Pembimbing'); ?>

<?php $__env->startSection('content'); ?>
                    <!-- Statistik -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-blue-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Mahasiswa Aktif</p>
                                    <p id="dospemMahasiswaAktif" class="text-3xl font-bold text-blue-600"><?php echo e($mahasiswaAktifCount); ?></p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-sky-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Tugas Review</p>
                                    <p id="dospemTugasReview" class="text-3xl font-bold text-sky-600"><?php echo e($tugasReview); ?></p>
                                </div>
                                <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-sky-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-teal-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Bimbingan Minggu Ini</p>
                                    <p id="dospemBimbinganMinggu" class="text-3xl font-bold text-teal-600"><?php echo e($bimbinganMingguIni); ?></p>
                                </div>
                                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-teal-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-indigo-400">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">TA Selesai</p>
                                    <p id="dospemTASelesai" class="text-3xl font-bold text-indigo-600"><?php echo e($taSelesai); ?></p>
                                </div>
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-trophy text-indigo-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Konten Utama -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Mahasiswa Bimbingan Aktif</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-blue-50 border-b border-blue-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">NIM</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Judul TA</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Progress</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dospemMahasiswaTable" class="divide-y divide-blue-50">
                                            <?php $__empty_1 = true; $__currentLoopData = $mahasiswaBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="hover:bg-blue-50 transition">
                                                <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($mhs->nim); ?></td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span class="font-bold text-blue-600"><?php echo e(strtoupper(substr($mhs->name, 0, 1))); ?></span>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-blue-900"><?php echo e($mhs->name); ?></p>
                                                            <p class="text-xs text-gray-500"><?php echo e($mhs->email); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <p class="text-sm text-gray-700"><?php echo e($mhs->judul_ta ?? 'Belum ada judul'); ?></p>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($mhs->progress); ?>%"></div>
                                                        </div>
                                                        <span class="text-sm font-bold text-blue-600"><?php echo e($mhs->progress); ?>%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada mahasiswa bimbingan</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Tugas Menunggu Review</h3>
                                <div id="dospemTugasContainer" class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $tugasMenungguReview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="flex items-center justify-between p-4 bg-sky-50 border-l-4 border-sky-400 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-blue-900"><?php echo e($tugas->judul); ?></p>
                                            <p class="text-xs text-gray-600 mt-1"><?php echo e($tugas->mahasiswa_nim); ?> - <?php echo e($tugas->mahasiswa_name); ?></p>
                                            <p class="text-xs text-sky-600 mt-1">Diajukan: <?php echo e($tugas->created_at); ?></p>
                                        </div>
                                        <button class="px-4 py-2 text-sm bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                            Review
                                        </button>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-center text-gray-500 py-4">Tidak ada tugas yang perlu direview</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Jadwal Bimbingan Mendatang</h3>
                                <div id="dospemJadwalContainer" class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $jadwalBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                                        <p class="font-semibold text-blue-900 text-sm"><?php echo e($jadwal->mahasiswa_nim); ?> - <?php echo e($jadwal->mahasiswa_name); ?></p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            <i class="far fa-calendar mr-1"></i><?php echo e($jadwal->tanggal); ?>

                                        </p>
                                        <p class="text-xs text-gray-600 mt-1"><?php echo e($jadwal->topik); ?></p>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-center text-gray-500 py-4">Tidak ada jadwal bimbingan</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        // Real-time polling for Dosen Pembimbing dashboard
        async function fetchDospemDashboardData() {
            try {
                const res = await fetch("<?php echo e(route('dospem.dashboard.data')); ?>", { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();

                // Update stat cards
                document.getElementById('dospemMahasiswaAktif').textContent = data.mahasiswaAktifCount ?? 0;
                document.getElementById('dospemTugasReview').textContent = data.tugasReview ?? 0;
                document.getElementById('dospemBimbinganMinggu').textContent = data.bimbinganMingguIni ?? 0;
                document.getElementById('dospemTASelesai').textContent = data.taSelesai ?? 0;

                // Update mahasiswa table with real progress
                const tableBody = document.getElementById('dospemMahasiswaTable');
                if (tableBody && data.mahasiswaBimbingan) {
                    tableBody.innerHTML = '';
                    if (data.mahasiswaBimbingan.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada mahasiswa bimbingan</td></tr>';
                    } else {
                        data.mahasiswaBimbingan.forEach(function(mhs) {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-blue-50 transition';
                            row.innerHTML = `
                                <td class="px-4 py-3 text-sm text-gray-700">${mhs.nim}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="font-bold text-blue-600">${mhs.name.charAt(0).toUpperCase()}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-blue-900">${mhs.name}</p>
                                            <p class="text-xs text-gray-500">${mhs.email}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-700">${mhs.judul_ta ?? 'Belum ada judul'}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${mhs.progress ?? 0}%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-blue-600">${mhs.progress ?? 0}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    }
                }

                // Update tugas container
                const tugasContainer = document.getElementById('dospemTugasContainer');
                if (tugasContainer && data.tugasMenungguReview) {
                    tugasContainer.innerHTML = '';
                    if (data.tugasMenungguReview.length === 0) {
                        tugasContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada tugas yang perlu direview</p>';
                    } else {
                        data.tugasMenungguReview.forEach(function(tugas) {
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-4 bg-sky-50 border-l-4 border-sky-400 rounded-lg';
                            div.innerHTML = `
                                <div>
                                    <p class="font-semibold text-blue-900">${tugas.judul}</p>
                                    <p class="text-xs text-gray-600 mt-1">${tugas.mahasiswa_nim} - ${tugas.mahasiswa_name}</p>
                                    <p class="text-xs text-sky-600 mt-1">Diajukan: ${tugas.created_at}</p>
                                </div>
                                <button class="px-4 py-2 text-sm bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                    Review
                                </button>
                            `;
                            tugasContainer.appendChild(div);
                        });
                    }
                }

                // Update jadwal container
                const jadwalContainer = document.getElementById('dospemJadwalContainer');
                if (jadwalContainer && data.jadwalBimbingan) {
                    jadwalContainer.innerHTML = '';
                    if (data.jadwalBimbingan.length === 0) {
                        jadwalContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada jadwal bimbingan</p>';
                    } else {
                        data.jadwalBimbingan.forEach(function(jadwal) {
                            const div = document.createElement('div');
                            div.className = 'p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg';
                            div.innerHTML = `
                                <p class="font-semibold text-blue-900 text-sm">${jadwal.mahasiswa_nim} - ${jadwal.mahasiswa_name}</p>
                                <p class="text-xs text-blue-600 mt-1"><i class="far fa-calendar mr-1"></i>${jadwal.tanggal}</p>
                                <p class="text-xs text-gray-600 mt-1">${jadwal.topik}</p>
                            `;
                            jadwalContainer.appendChild(div);
                        });
                    }
                }

                // Update header counters
                try {
                    const hdrMah = document.getElementById('headerMahasiswaAktifDospem');
                    if (hdrMah) hdrMah.textContent = (data.mahasiswaAktifCount ?? 0);
                    const hdrTugas = document.getElementById('headerTugasReviewDospem');
                    if (hdrTugas) hdrTugas.textContent = (data.tugasReview ?? 0);
                } catch (e) {
                    // ignore DOM errors
                }

            } catch (e) {
                console.error('Failed to fetch Dospem dashboard data', e);
            }
        }

        // Poll every 10 seconds
        setInterval(fetchDospemDashboardData, 10000);
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dospem.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/dospem/dashboard.blade.php ENDPATH**/ ?>
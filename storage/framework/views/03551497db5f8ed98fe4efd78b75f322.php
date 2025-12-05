<?php $__env->startSection('title', 'Dashboard Koordinator Prodi'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Statistik Global TA -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Statistik Global TA</h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg">Semester Ini</button>
                <button class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg">Tahun Ini</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Mahasiswa TA</p>
                        <p class="text-4xl font-bold text-blue-600"><?php echo e($totalMahasiswa); ?></p>
                    </div>
                    <i class="fas fa-users text-blue-600 text-3xl"></i>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Lulus</p>
                        <p class="text-4xl font-bold text-green-600"><?php echo e($mahasiswaLulus); ?></p>
                        <p class="text-xs text-green-600"><?php echo e($totalMahasiswa ? round(($mahasiswaLulus/$totalMahasiswa)*100, 1) : 0); ?>% dari total</p>
                    </div>
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-6 border-l-4 border-orange-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Belum Lulus</p>
                        <p class="text-4xl font-bold text-orange-600"><?php echo e($belumLulus); ?></p>
                        <p class="text-xs text-orange-600"><?php echo e($totalMahasiswa ? round(($belumLulus/$totalMahasiswa)*100, 1) : 0); ?>% dari total</p>
                    </div>
                    <i class="fas fa-hourglass-half text-orange-600 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Status per Semester</h4>
                <canvas id="semesterChart"></canvas>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Rata-rata Durasi TA</h4>
                <div class="space-y-4">
                    <?php $__currentLoopData = $rataDurasiTA; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600"><?php echo e($item['semester']); ?></span>
                            <span class="font-bold text-gray-800"><?php echo e($item['durasi']); ?> bulan</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(($item['durasi']/12)*100); ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="pt-4 border-t">
                        <p class="text-center text-sm text-gray-600">Rata-rata Keseluruhan: <span class="font-bold text-blue-600">8.4 bulan</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Alert & Peringatan</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Deadline Pendaftaran</p>
                            <p class="text-sm text-gray-600 mt-1">Pendaftaran TA semester baru berakhir dalam 3 hari</p>
                            <button class="text-xs text-red-600 font-medium mt-2">Prioritas Tinggi</button>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-lg">
                        <i class="fas fa-clock text-orange-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Sidang Tertunda</p>
                            <p class="text-sm text-gray-600 mt-1">19 mahasiswa belum dijadwalkan sidang</p>
                            <button class="text-xs text-orange-600 font-medium mt-2">Perlu Tindakan</button>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                        <i class="fas fa-user-clock text-yellow-600 mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Pembimbing Tidak Aktif</p>
                            <p class="text-sm text-gray-600 mt-1">3 dosen pembimbing belum memberikan feedback</p>
                            <button class="text-xs text-yellow-600 font-medium mt-2">Monitoring</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman Penting</h3>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $pengumumanPenting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                        <p class="font-semibold text-gray-800 text-sm"><?php echo e($item->judul); ?></p>
                        <p class="text-xs text-gray-600 mt-1"><?php echo e($item->tanggal); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center text-gray-500 py-4">Tidak ada pengumuman</p>
                    <?php endif; ?>
                    <button class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Buat Pengumuman Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Status per Semester
        const ctx = document.getElementById('semesterChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($chartData['labels']); ?>,
                    datasets: [{
                        label: 'Lulus',
                        data: <?php echo json_encode($chartData['lulus']); ?>,
                        backgroundColor: '#10b981',
                    }, {
                        label: 'Belum Lulus',
                        data: <?php echo json_encode($chartData['belum_lulus']); ?>,
                        backgroundColor: '#f59e0b',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('kaprodi.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tamago-ISI\resources\views/dashboards/kaprodi.blade.php ENDPATH**/ ?>
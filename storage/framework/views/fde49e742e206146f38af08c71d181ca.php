<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Mahasiswa - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-green-50 text-gray-800">
    <?php
        $mahasiswaAktifCount = 150; 
        $tugasReview = 25; 
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('koordinator_ta.partials.sidebar-koordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('koordinator_ta.partials.header-koordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Monitoring & Persetujuan Tahapan Mahasiswa</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-green-50 border-b border-green-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Judul TA</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Tahapan Saat Ini</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-green-50">
                                    <?php
                                    $mahasiswa = [
                                        ['nama' => 'Budi Santoso', 'judul' => 'Sistem Rekomendasi Film', 'tahapan' => 'Proposal Bab 1-3', 'status' => 'Menunggu Persetujuan'],
                                        ['nama' => 'Siti Lestari', 'judul' => 'Analisis Sentimen Media Sosial', 'tahapan' => 'Pra-Produksi', 'status' => 'Disetujui'],
                                        ['nama' => 'Ahmad Fauzi', 'judul' => 'Aplikasi Mobile untuk Petani', 'tahapan' => 'Produksi', 'status' => 'Disetujui'],
                                        ['nama' => 'Dewi Anggraini', 'judul' => 'Game Edukasi Sejarah', 'tahapan' => 'Proposal Bab 1-3', 'status' => 'Revisi'],
                                    ];
                                    ?>

                                    <?php $__currentLoopData = $mahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-green-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($mhs['nama']); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($mhs['judul']); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($mhs['tahapan']); ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($mhs['status'] == 'Menunggu Persetujuan'): ?>
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Menunggu</span>
                                            <?php elseif($mhs['status'] == 'Disetujui'): ?>
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Disetujui</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Revisi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($mhs['status'] == 'Menunggu Persetujuan'): ?>
                                                <button class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Setujui</button>
                                            <?php else: ?>
                                                <button class="px-3 py-1 text-xs bg-gray-400 text-white rounded cursor-not-allowed" disabled>Setujui</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\C\Tamago-ISI\resources\views/koordinator_ta/monitoring.blade.php ENDPATH**/ ?>
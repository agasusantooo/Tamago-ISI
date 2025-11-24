<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Ujian TA - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-purple-50 text-gray-800">
    <?php
        $mahasiswaAktifCount = 10; 
        $tugasReview = 5; 
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('dosen_penguji.partials.sidebar-dosen_penguji', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('dosen_penguji.partials.header-dosen_penguji', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4">Daftar Ujian TA untuk Dinilai</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-purple-50 border-b border-purple-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700">Judul TA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-purple-700">Tanggal Ujian</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-purple-700">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-purple-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-50">
                                    <?php
                                    $ujian = [
                                        ['id' => 1, 'nama' => 'Budi Santoso', 'judul' => 'Sistem Rekomendasi Film', 'tanggal' => '2025-11-20', 'status' => 'Belum Dinilai'],
                                        ['id' => 2, 'nama' => 'Siti Lestari', 'judul' => 'Analisis Sentimen Media Sosial', 'tanggal' => '2025-11-21', 'status' => 'Belum Dinilai'],
                                        ['id' => 3, 'nama' => 'Ahmad Fauzi', 'judul' => 'Aplikasi Mobile untuk Petani', 'tanggal' => '2025-11-19', 'status' => 'Sudah Dinilai'],
                                    ];
                                    ?>

                                    <?php $__currentLoopData = $ujian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-purple-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($item['nama']); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($item['judul']); ?></td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600"><?php echo e($item['tanggal']); ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($item['status'] == 'Belum Dinilai'): ?>
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Belum Dinilai</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Sudah Dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($item['status'] == 'Belum Dinilai'): ?>
                                                <button class="px-3 py-1 text-xs bg-purple-600 text-white rounded hover:bg-purple-700">
                                                    <i class="fas fa-edit mr-1"></i>Nilai
                                                </button>
                                            <?php else: ?>
                                                <button class="px-3 py-1 text-xs bg-gray-400 text-white rounded cursor-not-allowed" disabled>
                                                    <i class="fas fa-check mr-1"></i>Sudah Dinilai
                                                </button>
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
<?php /**PATH D:\Tamago-ISI\resources\views/dosen_penguji/penilaian.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-teal-50 text-gray-800">
    <?php
        $mahasiswaAktifCount = 250; 
        $tugasReview = 40; 
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('kaprodi.partials.sidebar-kaprodi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('kaprodi.partials.header-kaprodi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto space-y-6">
                    
                    <!-- Pengelolaan Rumpun Ilmu -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-teal-800 mb-4">Pengelolaan Rumpun Ilmu</h3>
                        <form action="#" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nama_rumpun" class="block text-sm font-medium text-gray-700">Nama Rumpun Ilmu</label>
                                    <input type="text" id="nama_rumpun" name="nama_rumpun" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Tambah Rumpun Ilmu</button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-teal-50 border-b border-teal-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-teal-700">Nama Rumpun Ilmu</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-teal-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-teal-50">
                                    <?php
                                    $rumpunIlmu = [
                                        ['id' => 1, 'nama' => 'Animasi'],
                                        ['id' => 2, 'nama' => 'Desain Komunikasi Visual'],
                                        ['id' => 3, 'nama' => 'Fotografi'],
                                    ];
                                    ?>
                                    <?php $__currentLoopData = $rumpunIlmu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rumpun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-teal-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($rumpun['nama']); ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <button class="px-2 py-1 text-xs text-blue-800 rounded hover:bg-blue-100"><i class="fas fa-edit"></i></button>
                                            <button class="px-2 py-1 text-xs text-red-800 rounded hover:bg-red-100"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pengelolaan Syarat Dosen Pembimbing -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-teal-800 mb-4">Pengelolaan Syarat Dosen Pembimbing</h3>
                        <form action="#" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <div>
                                <label for="syarat_dospem" class="block text-sm font-medium text-gray-700">Syarat Dosen Pembimbing</label>
                                <textarea id="syarat_dospem" name="syarat_dospem" rows="5" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">Minimal S2, memiliki publikasi ilmiah relevan, pengalaman membimbing 3 mahasiswa.</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Simpan Syarat</button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\Tamago-ISI\resources\views/kaprodi/pengelolaan.blade.php ENDPATH**/ ?>
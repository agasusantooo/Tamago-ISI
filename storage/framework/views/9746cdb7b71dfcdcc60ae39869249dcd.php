<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Semester & Timeline - Tamago ISI</title>
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
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-teal-800 mb-4">Setup Semester & Timeline Tugas Akhir</h3>
                        
                        <form action="#" method="POST" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Semester & Tahun Akademik -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                                    <select id="semester" name="semester" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                        <option value="ganjil">Ganjil</option>
                                        <option value="genap">Genap</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="tahun_akademik" class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                                    <input type="text" id="tahun_akademik" name="tahun_akademik" value="2025/2026" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                            </div>

                            <!-- Tanggal Mulai & Akhir Semester -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="tgl_mulai_semester" class="block text-sm font-medium text-gray-700">Tanggal Mulai Semester</label>
                                    <input type="date" id="tgl_mulai_semester" name="tgl_mulai_semester" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="tgl_akhir_semester" class="block text-sm font-medium text-gray-700">Tanggal Akhir Semester</label>
                                    <input type="date" id="tgl_akhir_semester" name="tgl_akhir_semester" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                            </div>

                            <hr class="my-6">

                            <h4 class="text-md font-semibold text-gray-800 mb-3">Timeline Tugas Akhir</h4>

                            <!-- Batas Akhir Pengajuan Proposal -->
                            <div>
                                <label for="batas_proposal" class="block text-sm font-medium text-gray-700">Batas Akhir Pengajuan Proposal</label>
                                <input type="date" id="batas_proposal" name="batas_proposal" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            </div>

                            <!-- Batas Akhir Story Conference -->
                            <div>
                                <label for="batas_story_conference" class="block text-sm font-medium text-gray-700">Batas Akhir Story Conference</label>
                                <input type="date" id="batas_story_conference" name="batas_story_conference" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            </div>

                            <!-- Batas Akhir TEFA Fair -->
                            <div>
                                <label for="batas_tefa_fair" class="block text-sm font-medium text-gray-700">Batas Akhir TEFA Fair</label>
                                <input type="date" id="batas_tefa_fair" name="batas_tefa_fair" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            </div>

                            <!-- Batas Akhir Ujian TA -->
                            <div>
                                <label for="batas_ujian_ta" class="block text-sm font-medium text-gray-700">Batas Akhir Ujian TA</label>
                                <input type="date" id="batas_ujian_ta" name="batas_ujian_ta" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition">Simpan Pengaturan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\Tamago-ISI\resources\views/kaprodi/setup.blade.php ENDPATH**/ ?>
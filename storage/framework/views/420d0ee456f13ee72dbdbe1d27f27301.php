<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-green-50 text-gray-800">
    <?php
        // Dummy data untuk menghindari error di header
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
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Dashboard Koordinator TA</h3>
                        <p>Selamat datang di dashboard Koordinator Tugas Akhir. Silakan gunakan menu di samping untuk mengelola jadwal, memonitor mahasiswa, dan mengatur mata kuliah.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html><?php /**PATH D:\Tamago-ISI\resources\views/koordinator_ta/dashboard.blade.php ENDPATH**/ ?>
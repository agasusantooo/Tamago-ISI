<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen Penguji - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-purple-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        
        <?php echo $__env->make('dosen_penguji.partials.sidebar-dosen_penguji', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('dosen_penguji.partials.header-dosen_penguji', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4">Dashboard Dosen Penguji</h3>
                        <p>This is the dashboard for Dosen Penguji.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html><?php /**PATH D:\Tamago-ISI\resources\views/dosen_penguji/dashboard.blade.php ENDPATH**/ ?>
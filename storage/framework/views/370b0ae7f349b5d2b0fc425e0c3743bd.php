<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Dosen Pembimbing - Tamago ISI'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <?php echo $__env->make('dospem.partials.sidebar-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php echo $__env->make('dospem.partials.header-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\C\Tamago-ISI\resources\views/dospem/layouts/app.blade.php ENDPATH**/ ?>
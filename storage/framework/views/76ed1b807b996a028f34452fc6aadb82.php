<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard Mahasiswa'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        
        <?php echo $__env->make('mahasiswa.partials.sidebar-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="flex-1 flex flex-col overflow-hidden">

            
            <?php echo $__env->make('mahasiswa.partials.header-mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                
                <?php if(session('success')): ?>
                    <div class="max-w-7xl mx-auto mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                        <p class="text-green-800 font-semibold"><?php echo e(session('success')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="max-w-7xl mx-auto mb-4 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <p class="text-red-800 font-semibold"><?php echo e(session('error')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="max-w-7xl mx-auto mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <ul class="list-disc list-inside text-sm text-yellow-800">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($err); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        if (sidebarToggle) {
            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.toggle("hidden");
            });
        }
    </script>
</body>
</html>
<?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/layouts/app.blade.php ENDPATH**/ ?>
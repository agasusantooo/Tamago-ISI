<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS (tanpa Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    
    <nav class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                <?php echo e(config('app.name', 'Dashboard')); ?>

            </h1>
            <div class="space-x-4">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-600 dark:text-gray-300 hover:text-blue-500">Dashboard</a>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-gray-600 dark:text-gray-300 hover:text-red-500">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    
    <?php if(isset($header)): ?>
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <?php echo e($header); ?>

            </div>
        </header>
    <?php endif; ?>

    
    <main>
        <?php echo e($slot); ?>

    </main>
</body>
</html>
<?php /**PATH C:\laragon\www\Tamago-ISI-main\Tamago-ISI-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>
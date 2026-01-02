<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="antialiased bg-white text-gray-900">
    <!-- Halaman login/register hanya slot -->
    <div class="min-h-screen flex flex-col justify-center items-center">
        <?php echo e($slot); ?>

    </div>
</body>
</html>
<?php /**PATH D:\C\Tamago-ISI\resources\views\components\layouts\guest.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-100 to-blue-100 py-8">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-6">
            <div class="w-24 h-24 mx-auto bg-blue-900 text-white flex items-center justify-center rounded-lg font-semibold text-sm">
                ISI<br>YOGYAKARTA
            </div>
            <h1 class="text-2xl font-bold mt-4">Create Account</h1>
            <p class="text-gray-600 text-sm">Daftar untuk mengakses Tamago ISI</p>
        </div>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Register -->
        <form method="POST" action="<?php echo e(route('register.post')); ?>">
            <?php echo csrf_field(); ?>
            
            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>"
                    class="w-full p-3 border rounded-lg bg-gray-100 focus:bg-white focus:ring-2 focus:ring-blue-500" 
                    placeholder="Masukkan nama lengkap" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                    class="w-full p-3 border rounded-lg bg-gray-100 focus:bg-white focus:ring-2 focus:ring-blue-500" 
                    placeholder="nama@email.com" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block font-medium mb-1">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full p-3 border rounded-lg bg-gray-100 focus:bg-white focus:ring-2 focus:ring-blue-500" 
                    placeholder="Minimal 8 karakter" required>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full p-3 border rounded-lg bg-gray-100 focus:bg-white focus:ring-2 focus:ring-blue-500" 
                    placeholder="Ketik ulang password" required>
            </div>

            <!-- Button Register -->
            <button type="submit"
                class="w-full bg-blue-900 text-white font-semibold py-3 rounded-lg hover:bg-blue-800 transition">
                Sign Up
            </button>

            <!-- Link Login -->
            <p class="text-center text-sm mt-4">
                Already have an account?
                <a href="<?php echo e(route('login')); ?>" class="font-medium underline hover:text-blue-800">Sign In</a>
            </p>
        </form>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\Tamago-ISI-main\Tamago-ISI-main\resources\views/auth/register.blade.php ENDPATH**/ ?>
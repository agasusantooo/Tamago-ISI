<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-100 to-blue-100">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-6">
            <div class="w-24 h-24 mx-auto bg-blue-900 text-white flex items-center justify-center rounded-lg font-semibold text-sm">
                ISI<br>YOGYAKARTA
            </div>
            <h1 class="text-2xl font-bold mt-4">Welcome Back</h1>
        </div>

        <!-- Error Message -->
        <?php if($errors->any()): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center text-sm">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST" action="<?php echo e(route('login.post')); ?>">
            <?php echo csrf_field(); ?>
            
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
                    placeholder="Masukkan password" required>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>
                <a href="#" class="text-sm text-blue-700 hover:underline">Forgot Password?</a>
            </div>

            <!-- Button Login -->
            <button type="submit"
                class="w-full bg-blue-900 text-white font-semibold py-3 rounded-lg hover:bg-blue-800 transition">
                Sign In
            </button>

            <!-- Link Register -->
            <p class="text-center text-sm mt-4">
                Don't have an account?
                <a href="<?php echo e(route('register')); ?>" class="font-medium underline hover:text-blue-800">Sign Up</a>
            </p>
        </form>
    </div>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/auth/login.blade.php ENDPATH**/ ?>
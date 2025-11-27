<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-gray-100 to-blue-100 py-10">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-6">
            <img src="<?php echo e(asset('images/logo-isi.png')); ?>" alt="Logo ISI Yogyakarta" class="w-24 h-24 mx-auto">
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

    <?php
    $leaderboardData = [
        (object)['name' => 'Budi Santoso', 'progress' => 95, 'rank' => 1],
        (object)['name' => 'Siti Lestari', 'progress' => 92, 'rank' => 2],
        (object)['name' => 'Ahmad Fauzi', 'progress' => 88, 'rank' => 3],
        (object)['name' => 'Dewi Anggraini', 'progress' => 85, 'rank' => 4],
        (object)['name' => 'Rian Hidayat', 'progress' => 82, 'rank' => 5],
        (object)['name' => 'Eka Putri', 'progress' => 80, 'rank' => 6],
        (object)['name' => 'Fajar Nugraha', 'progress' => 78, 'rank' => 7],
        (object)['name' => 'Gita Permata', 'progress' => 75, 'rank' => 8],
        (object)['name' => 'Hendra Wijaya', 'progress' => 71, 'rank' => 9],
        (object)['name' => 'Indah Sari', 'progress' => 68, 'rank' => 10],
    ];
    ?>
    <!-- Leaderboard Panel -->
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md mt-8">
        <h3 class="text-lg font-semibold text-center mb-4">Papan Peringkat Progress</h3>
        <div class="h-80 overflow-y-auto"> <!-- Fixed height and scroll -->
            <ul class="space-y-3">
                <?php $__currentLoopData = $leaderboardData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-center p-2 pr-4 bg-gray-50 rounded-lg">
                        <span class="text-lg font-bold text-gray-400 w-10 text-center"><?php echo e($student->rank); ?></span>
                        <div class="ml-2 flex-1">
                            <p class="font-semibold text-gray-800"><?php echo e($student->name); ?></p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo e($student->progress); ?>%"></div>
                            </div>
                        </div>
                        <span class="ml-4 font-bold text-blue-600"><?php echo e($student->progress); ?>%</span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/auth/login.blade.php ENDPATH**/ ?>
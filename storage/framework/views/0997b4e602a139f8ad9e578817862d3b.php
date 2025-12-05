<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-blue-50 text-gray-800">
    <?php
        $mahasiswaAktifCount = 15; 
        $tugasReview = 5; 
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('dospem.partials.sidebar-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('dospem.partials.header-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Akun Saya</h2>

                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <form action="#" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Profile Picture -->
                                <div class="md:col-span-1 flex flex-col items-center">
                                    <img src="<?php echo e(asset('images/user.png')); ?>" alt="Avatar" class="w-32 h-32 rounded-full mb-4">
                                    <button type="button" class="text-sm text-blue-600 hover:underline">Ganti Foto</button>
                                </div>

                                <!-- Form Fields -->
                                <div class="md:col-span-2 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" value="<?php echo e(Auth::user()->name); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                        <input type="email" name="email" id="email" value="<?php echo e(Auth::user()->email); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-8">

                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" autocomplete="new-password">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\C\Tamago-ISI\resources\views/dospem/profile.blade.php ENDPATH**/ ?>
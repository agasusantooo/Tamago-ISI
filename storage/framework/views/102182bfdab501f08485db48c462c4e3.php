

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="mb-6 text-center">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="w-20 mx-auto mb-4">
        <h2 class="text-xl font-semibold text-black">Welcome Back</h2>
    </div>

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow">
        <form wire:submit="login">
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input wire:model="form.email" id="email" type="email" required autofocus
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input wire:model="form.password" id="password" type="password" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center">
                    <input wire:model="form.remember" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600">
                    <span class="ml-2 text-sm text-gray-600">Remember Me</span>
                </label>

                <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-indigo-600 hover:underline">
                    Forgot Password?
                </a>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Don't have an account?
            <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:underline">Sign Up</a>
        </p>
    </div>
</div>
<?php /**PATH D:\Tamago-ISI\resources\views/livewire/pages/auth/login.blade.php ENDPATH**/ ?>
<a href="<?php echo e(route('profile.edit')); ?>" class="flex flex-col items-center focus:outline-none transition">
    <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : '/images/user.png'); ?>" alt="User Icon" class="w-9 h-9 rounded-full object-cover">
    <span class="text-gray-600 text-xs mt-1"><?php echo e(Auth::user()->name); ?></span>
</a><?php /**PATH D:\Tamago-ISI\resources\views/components/user-dropdown.blade.php ENDPATH**/ ?>
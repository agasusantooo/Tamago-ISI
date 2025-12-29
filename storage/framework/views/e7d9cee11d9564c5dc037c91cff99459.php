<div x-data="{open:false}" @click.outside="open = false" @keydown.escape.window="open = false" class="relative">
    <!-- Toggle button: explicit type and ARIA for accessibility -->
    <!-- Use @click.stop so the click doesn't bubble and trigger the outside handler -->
    <button type="button" @click.stop="open = !open" :aria-expanded="open ? 'true' : 'false'" aria-haspopup="true" class="flex items-center space-x-3 focus:outline-none">
        <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : '/images/user.png'); ?>" alt="User Icon" class="w-9 h-9 rounded-full object-cover">
        <span class="text-gray-600 text-sm"><?php echo e(Auth::user()->name); ?></span>
    </button>

    <!-- Menu: rely on x-show/x-cloak, allow inside clicks to not close automatically -->
    <!-- Add static `hidden` so the menu stays hidden before Alpine initializes -->
    <div hidden x-show="open" x-cloak :hidden="!open" @click.stop x-transition class="absolute right-0 mt-2 w-40 bg-white border border-gray-100 rounded-lg shadow-lg z-50">
        <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Keluar</button>
        </form>
    </div>
</div><?php /**PATH D:\C\Tamago-ISI\resources\views/components/user-dropdown.blade.php ENDPATH**/ ?>
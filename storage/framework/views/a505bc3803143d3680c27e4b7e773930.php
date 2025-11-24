<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['menu' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['menu' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Sidebar -->
<aside class="w-64 bg-white shadow-md flex flex-col" id="sidebar">
    <!-- Logo -->
    <div class="p-6 border-b">
        <div class="flex items-center space-x-3">
            <img src="<?php echo e(asset('images/logo-isi.png')); ?>" 
                 alt="Logo ISI" 
                 class="w-12 h-12 object-contain rounded-lg">
            <div>
                <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                <p class="text-xs text-gray-500"><?php echo e(Auth::user()->getRoleDisplayName()); ?></p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition 
                       <?php echo e(request()->routeIs($item['route']) ? ($item['active_class'] ?? 'bg-gray-100 text-gray-900') : 'text-gray-700 hover:bg-gray-50'); ?>">
                        <i class="<?php echo e($item['icon']); ?> w-5"></i><span><?php echo e($item['name']); ?></span>
                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </nav>

    <!-- User Info & Logout -->
    <div class="p-4 border-t">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate"><?php echo e(Auth::user()->name); ?></p>
                <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
<?php /**PATH D:\Tamago-ISI\resources\views/components/sidebar.blade.php ENDPATH**/ ?>
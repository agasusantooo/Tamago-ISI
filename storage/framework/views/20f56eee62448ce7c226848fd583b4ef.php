<header class="bg-white border-b shadow-md">
    <div class="flex items-center px-6 py-4">
        <div class="flex-1 mr-8 flex items-center space-x-3">
            <img src="/images/logo-isi.png" alt="Logo ISI" class="w-10 h-10 object-contain rounded-lg">
            <div>
                <h1 class="text-lg font-semibold text-gray-700">Koordinator TEFA</h1>
                <div class="mt-2 mb-1">
                    
                </div>
                <div class="h-3"></div>
            </div>
        </div>
    
        <?php if (isset($component)) { $__componentOriginald26e54664725015b4d5304353f34e090 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald26e54664725015b4d5304353f34e090 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.user-dropdown','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('user-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald26e54664725015b4d5304353f34e090)): ?>
<?php $attributes = $__attributesOriginald26e54664725015b4d5304353f34e090; ?>
<?php unset($__attributesOriginald26e54664725015b4d5304353f34e090); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald26e54664725015b4d5304353f34e090)): ?>
<?php $component = $__componentOriginald26e54664725015b4d5304353f34e090; ?>
<?php unset($__componentOriginald26e54664725015b4d5304353f34e090); ?>
<?php endif; ?>
    </div>
</header>
<?php /**PATH D:\C\Tamago-ISI\resources\views/koordinator_tefa/partials/header-koordinator.blade.php ENDPATH**/ ?>
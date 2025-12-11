<header class="bg-white border-b flex items-center px-6 py-4">
    <div class="flex-1 mr-8">
        <h1 class="text-lg font-semibold text-gray-700"><?php echo e($pageTitle ?? (View::hasSection('page-title') ? View::yieldContent('page-title') : 'Progress Tugas Akhir')); ?></h1>
        <?php if(!isset($hideProgressBar) || !$hideProgressBar): ?>
        <div class="flex items-center mt-2 mb-1">

            <?php
                $headerPct = isset($progress) ? (int) max(0, min(100, $progress)) : (isset($latestProposal) && $latestProposal->status == 'disetujui' ? 100 : 0);
            ?>
            <div class="flex-1 bg-gray-100 h-3 rounded-full">
                <div id="headerProgressBarInner" class="bg-yellow-700 h-3 rounded-full transition-all duration-500"
                     style="width: <?php echo e($headerPct); ?>%;"></div>
            </div>
            <p id="headerProgressPct" class="text-xs font-semibold text-yellow-800 ml-2">
                <?php echo e($headerPct); ?>%
            </p>
        </div>
        <?php else: ?>
        <div class="h-3"></div>
        <?php endif; ?>
        <div class="h-3"></div>
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
</header>
<?php /**PATH D:\Tamago-ISI\resources\views/mahasiswa/partials/header-mahasiswa.blade.php ENDPATH**/ ?>
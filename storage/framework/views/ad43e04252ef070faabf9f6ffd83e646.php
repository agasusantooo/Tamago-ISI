<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator Story Conference - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-green-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('koordinator_story_conference.partials.sidebar-koordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            
            <?php echo $__env->make('koordinator_ta.partials.header-koordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Dashboard Koordinator Story Conference</h3>
                        </div>

                        <!-- Stats (Story Conference specific) -->
                        <div class="col-span-12 grid grid-cols-3 gap-4 mb-4">
                            <div class="bg-white rounded-xl shadow p-5">
                                <p class="text-sm text-gray-500">Total Pendaftar</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e($totalRegistrations ?? 0); ?></p>
                                <p class="text-xs text-green-500 mt-1">Total peserta yang mendaftar story conference</p>
                            </div>

                            <div class="bg-white rounded-xl shadow p-5">
                                <p class="text-sm text-gray-500">Diterima</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e($accepted ?? 0); ?></p>
                                <p class="text-xs text-blue-500 mt-1"><?php echo e($totalRegistrations ? round(($accepted/$totalRegistrations)*100, 1) : 0); ?>% dari total</p>
                            </div>

                            <div class="bg-white rounded-xl shadow p-5">
                                <p class="text-sm text-gray-500">Menunggu Persetujuan</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e($pendingCount ?? 0); ?></p>
                                <p class="text-xs text-green-500 mt-1">Perlu tindakan panitia</p>
                            </div>
                        </div>

                        <!-- Pending tasks and upcoming events -->
                        <div class="col-span-8">
                            <div class="bg-white rounded-xl shadow p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold">Tugas Menunggu Persetujuan</h4>
                                    <span class="text-xs text-gray-500"><?php echo e(collect($pending['story_conference'] ?? [])->count()); ?> Pending</span>
                                </div>

                                <div class="space-y-3">
                                    <?php $__currentLoopData = $pending['story_conference']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                            <div>
                                                <div class="text-sm font-medium">Pendaftaran Story Conference</div>
                                                <div class="text-xs text-gray-600">Mahasiswa: <?php echo e(optional(optional($s->resolved_mahasiswa)->user)->name ?? optional($s->resolved_mahasiswa)->nama ?? 'N/A'); ?> (<?php echo e(optional($s->resolved_mahasiswa)->nim ?? 'N/A'); ?>)</div>
                                            </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="<?php echo e(route('koordinator_story_conference.monitoring')); ?>" class="px-3 py-1 bg-green-600 text-white rounded">✓</a>
                                            <a href="<?php echo e(route('koordinator_story_conference.monitoring')); ?>" class="px-3 py-1 bg-red-600 text-white rounded">✕</a>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming events -->
                        <div class="col-span-4">
                            <div class="bg-white rounded-xl shadow p-4">
                                <h4 class="font-semibold mb-3">Jadwal Mendatang</h4>
                                <div class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-3 rounded border-l-4 border-green-200 bg-green-50">
                                        <div class="text-sm font-semibold"><?php echo e(\Carbon\Carbon::parse($jadwal->start)->format('d M')); ?></div>
                                        <div class="text-sm"><?php echo e($jadwal->title); ?> • <?php echo e(\Carbon\Carbon::parse($jadwal->start)->format('H:i')); ?> • <?php echo e($jadwal->type ?? 'Acara'); ?></div>
                                        <div class="text-xs text-gray-600"><?php echo e(optional($jadwal)->description ?? ''); ?></div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-sm text-gray-500">Tidak ada jadwal mendatang.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Announcements -->
                        <div class="col-span-12 mt-4">
                            <div class="bg-white rounded-xl shadow p-4">
                                <h4 class="font-semibold mb-3">Pengumuman Global</h4>
                                <div class="space-y-3">
                                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-4 rounded bg-yellow-50 border border-yellow-100">
                                        <div class="font-semibold"><?php echo e($a['title']); ?></div>
                                        <div class="text-sm text-gray-700 mt-1"><?php echo e($a['body']); ?></div>
                                        <div class="text-xs text-gray-500 mt-2"><?php echo e($a['meta']); ?></div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/koordinator_story_conference/dashboard.blade.php ENDPATH**/ ?>
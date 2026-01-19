<?php $__env->startSection('title', 'Monitoring Pendaftar Story Conference'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-green-800 mb-4">Monitoring & Persetujuan Pendaftar Story Conference</h3>
        
        <?php if(session('success')): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50 border-b border-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-green-700">Judul Proposal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-green-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-green-50">
                    <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e(optional($registration->resolved_mahasiswa)->nim ?? 'N/A'); ?></td>
                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e(optional(optional($registration->resolved_mahasiswa)->user)->name ?? optional($registration->resolved_mahasiswa)->nama ?? 'N/A'); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($registration->proposal->judul ?? 'N/A'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full"><?php echo e(Str::title(str_replace('_', ' ', $registration->status ?? 'Belum ada status'))); ?></span>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <form action="<?php echo e(route('koordinator_story_conference.monitoring.approve', $registration->getKey())); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Setujui</button>
                            </form>
                            <form action="<?php echo e(route('koordinator_story_conference.monitoring.reject', $registration->getKey())); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="reason" class="reject-reason-input">
                                <button type="submit" class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 reject-btn">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada pendaftar Story Conference untuk ditampilkan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
    <?php $__env->startPush('scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.reject-btn').forEach(function(btn) {
            btn.addEventListener('click', function (e) {
                var reason = prompt('Alasan penolakan (opsional, minimal 5 karakter jika diisi):');
                if (reason === null) { e.preventDefault(); return; }
                if (reason && reason.length < 5) { alert('Alasan penolakan harus minimal 5 karakter jika diisi.'); e.preventDefault(); return; }
                var input = btn.closest('form').querySelector('.reject-reason-input');
                if (input) input.value = reason;
            });
        });
    });
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('koordinator_story_conference.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/koordinator_story_conference/monitoring.blade.php ENDPATH**/ ?>
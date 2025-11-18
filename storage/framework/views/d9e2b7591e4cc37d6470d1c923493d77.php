<div wire:poll.5s>
    <?php
        // guard against undefined variables when this blade is accidentally rendered outside Livewire
        $timeline = $timeline ?? [];
        $status = $status ?? ['text' => 'Tidak ada status', 'variant' => 'yellow'];
        // guard newly added Livewire props
        $hasUjian = $hasUjian ?? false;
        $ujianStatus = $ujianStatus ?? null;
    ?>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h4 class="font-semibold mb-3">Timeline Ujian</h4>
        <ul class="space-y-3 text-sm text-gray-700">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="flex items-start">
                    <?php
                        $color = $item['color'] ?? 'gray';
                        $dotClass = $color === 'green' ? 'bg-green-500' : ($color === 'blue' ? 'bg-blue-500' : 'bg-gray-300');
                    ?>
                    <span class="w-3 h-3 <?php echo e($dotClass); ?> rounded-full mr-3 mt-1"></span>
                    <div>
                        <div class="font-semibold"><?php echo e($item['title']); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($item['date']); ?></div>
                    </div>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="text-sm text-gray-500">Belum ada aktivitas.</li>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </ul>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h4 class="font-semibold mb-3">Status Pengajuan</h4>
        <?php
            // Friendly mappings for pendaftaran statuses
            $pendaftaranMap = [
                'pengajuan_ujian' => ['label' => 'Pengajuan Ujian', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                'jadwal_ditetapkan' => ['label' => 'Jadwal Ditetapkan', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                'ujian_berlangsung' => ['label' => 'Ujian Berlangsung', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                'belum_ujian' => ['label' => 'Belum Ujian', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                'selesai_ujian' => ['label' => 'Selesai Ujian', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
            ];

            // Determine current status info from the available ujianTA if present, else fallback to $status
            $currentPendaftaran = null;
            if (!empty($ujianTA)) {
                $key = $ujianTA->status_pendaftaran ?? null;
                $currentPendaftaran = $pendaftaranMap[$key] ?? ['label' => ucfirst(str_replace('_',' ',$key ?? 'Tidak ada status')), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
            } else {
                $variant = $status['variant'] ?? 'yellow';
                $currentPendaftaran = ['label' => $status['text'] ?? 'Tidak ada status', 'bg' => 'bg-'.$variant.'-50', 'text' => 'text-'.$variant.'-800'];
            }
        ?>

        <div class="px-3 py-4 rounded <?php echo e($currentPendaftaran['bg']); ?>">
            <div class="font-semibold <?php echo e($currentPendaftaran['text']); ?>"><?php echo e($currentPendaftaran['label']); ?></div>
            <div class="text-sm text-gray-600 mt-2">Setelah mengajukan, pengajuan Anda akan diverifikasi oleh admin dalam 1-3 hari kerja.</div>
        </div>
        
        <?php $user = auth()->user(); ?>
        <!--[if BLOCK]><![endif]--><?php if($user && ($user->isAdmin() || $user->isDospem() || $user->isDosenPenguji() || $user->isKaprodi() || $user->isKoordinatorTA())): ?>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status Pendaftaran</label>
                <div class="flex items-center gap-2">
                    <select wire:model.defer="selectedStatus" class="border rounded px-3 py-2 text-sm">
                        <option value="">-- Pilih Status --</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $allowedStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php
                                $label = $pendaftaranMap[$key]['label'] ?? ucwords(str_replace('_',' ',$key));
                            ?>
                            <option value="<?php echo e($key); ?>" <?php if(($ujianTA->status_pendaftaran ?? null) == $key): ?> selected <?php endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <button wire:click="updateStatus" class="px-3 py-2 bg-green-600 text-white rounded text-sm">Simpan</button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <div class="mt-4 text-center">
            <!--[if BLOCK]><![endif]--><?php if($hasUjian && $ujianStatus === 'selesai_ujian'): ?>
                <a href="<?php echo e(route('mahasiswa.ujian-ta.hasil')); ?>" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded shadow text-sm hover:bg-indigo-700">Lihat Hasil Ujian</a>
            <?php elseif($hasUjian): ?>
                <span class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">Hasil akan tersedia setelah ujian selesai</span>
            <?php else: ?>
                <span class="inline-block px-4 py-2 bg-gray-100 text-gray-600 rounded text-sm">Belum terdaftar ujian</span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
 </div>

<script>
    // Simple browser event handler fallback for Livewire notifications
    window.addEventListener('notify', function(e) {
        try {
            var detail = e.detail || {};
            var message = detail.message || 'Notifikasi';
            // prefer a nicer toast if available, otherwise simple alert
            if (window.toastr) {
                if (detail.type === 'success') toastr.success(message);
                else if (detail.type === 'error') toastr.error(message);
                else toastr.info(message);
            } else {
                alert(message);
            }
        } catch (ex) {
            // noop
        }
    });
</script><?php /**PATH C:\laragon\www\tam\ooo\Tamago-ISI-main\resources\views\livewire/mahasiswa/ujian-timeline.blade.php ENDPATH**/ ?>
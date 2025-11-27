<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Tugas - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('dospem.partials.sidebar-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('dospem.partials.header-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6" x-data="reviewModal()">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Tugas Mahasiswa untuk Direview</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50 border-b border-blue-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Jenis Tugas</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Tanggal Submit</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $bimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $taskData = [
                                            "id" => $item->id,
                                            "mahasiswa" => $item->mahasiswa_name,
                                            "topik" => $item->topik,
                                            "tanggal" => $item->created_at?->format("Y-m-d"),
                                            "status" => $item->status
                                        ];
                                    ?>
                                    <tr class="hover:bg-blue-50 transition" data-review-id="<?php echo e($item->id); ?>">
                                        <td class="px-4 py-3"><p class="font-medium text-blue-900"><?php echo e($item->mahasiswa_name); ?></p></td>
                                        <td class="px-4 py-3"><p class="text-sm text-gray-700"><?php echo e($item->topik); ?></p></td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600"><?php echo e($item->created_at?->format('Y-m-d') ?? '-'); ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <button @click='selectedTask = <?php echo json_encode($taskData, 15, 512) ?>; showModal = true' class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                                <i class="fas fa-pen-to-square mr-1"></i>Review
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada tugas yang perlu direview.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div x-show="showModal" style="display: none;" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                        <div @click="showModal = false" x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-8 my-20 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Review Tugas: <span x-text="selectedTask ? selectedTask.topik : ''"></span></h3>
                            <p class="text-sm text-gray-600">Mahasiswa: <span x-text="selectedTask ? selectedTask.mahasiswa : ''"></span></p>
                            <p class="text-xs text-gray-400">Tanggal: <span x-text="selectedTask ? selectedTask.tanggal : ''"></span></p>
                            
                            <div class="mt-4">
                                <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback atau Komentar</label>
                                <textarea id="feedback" name="feedback" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Tuliskan feedback Anda di sini..."></textarea>
                            </div>

                            <div class="mt-6 flex justify-between items-center">
                                <div class="flex gap-3">
                                    <button @click="showModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Tutup</button>
                                    <button type="button" @click="submitReview('reject')" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Revisi Ditolak</button>
                                    <button type="button" @click="submitReview('approve')" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Setujui</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
    
    <script>
        function reviewModal() {
            return {
                showModal: false,
                selectedTask: null,
                async submitReview(action) {
                    const id = this.selectedTask ? this.selectedTask.id : null;
                    const alasan = document.getElementById('feedback') ? document.getElementById('feedback').value : '';
                    if (!id) {
                        alert('ID tugas tidak ditemukan.');
                        return;
                    }

                    const approveTemplate = `<?php echo e(route('dospem.bimbingan.approve', ['id' => 'PLACEHOLDER'])); ?>`;
                    const rejectTemplate = `<?php echo e(route('dospem.bimbingan.reject', ['id' => 'PLACEHOLDER'])); ?>`;
                    const url = action === 'approve' ? approveTemplate.replace('PLACEHOLDER', id) : rejectTemplate.replace('PLACEHOLDER', id);

                    const form = new URLSearchParams();
                    form.append('alasan', alasan);
                    form.append('_token', '<?php echo e(csrf_token()); ?>');

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: form
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) {
                            this.showModal = false;
                            // Remove row from table or mark as reviewed
                            const row = document.querySelector(`[data-review-id="${id}"]`);
                            if (row) row.remove();
                            // Simple feedback
                            alert(data.message || 'Aksi berhasil.');
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat memproses.');
                        }
                    } catch (e) {
                        alert('Koneksi gagal: ' + e.message);
                    }
                }
            }
        }
    </script>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/dospem/review-tugas.blade.php ENDPATH**/ ?>
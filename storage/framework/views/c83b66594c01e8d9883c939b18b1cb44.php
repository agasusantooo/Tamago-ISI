<?php $__env->startSection('page-title', 'Progress Tugas Akhir'); ?>

<?php $__env->startSection('title', 'Hasil Ujian TA'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Hasil Ujian Tugas Akhir</h2>

                
                <?php
                    $statusUjian = strtolower(str_replace([' ', '-'], '_', $ujianTA->status_ujian ?? ''));
                    $statusPendaftaran = strtolower(str_replace([' ', '-'], '_', $ujianTA->status_pendaftaran ?? ''));
                    
                    if (strpos($statusUjian, 'lulus') !== false) {
                        $statusColor = 'green';
                        $statusLabel = 'Lulus';
                        $statusIcon = 'check-circle';
                    } elseif (strpos($statusUjian, 'tidak') !== false || strpos($statusUjian, 'gagal') !== false) {
                        $statusColor = 'red';
                        $statusLabel = 'Tidak Lulus';
                        $statusIcon = 'times-circle';
                    } else {
                        $statusColor = 'yellow';
                        $statusLabel = 'Perlu Revisi';
                        $statusIcon = 'exclamation-circle';
                    }
                ?>

                <div class="bg-<?php echo e($statusColor); ?>-50 border-l-4 border-<?php echo e($statusColor); ?>-300 rounded p-4 mb-6">
                    <div class="flex items-start gap-4">
                        <i class="fas fa-<?php echo e($statusIcon); ?> text-<?php echo e($statusColor); ?>-700 mt-1"></i>
                        <div>
                            <div class="text-<?php echo e($statusColor); ?>-700 font-semibold">Status: <?php echo e($statusLabel); ?></div>
                            <div class="text-sm text-gray-700">
                                <?php if(strpos($statusUjian, 'lulus') !== false): ?>
                                    Selamat! Ujian Anda dinyatakan lulus. Nilai akhir dan berita acara dapat didownload di bawah.
                                <?php elseif(strpos($statusUjian, 'revisi') !== false): ?>
                                    Ujian Anda memerlukan beberapa perbaikan sebelum dapat dinyatakan lulus. Silakan lihat catatan feedback di bawah ini.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <?php if(!empty($ujianTA->catatan_penguji)): ?>
                        <div class="mt-4 bg-white rounded p-4 border">
                            <h4 class="font-semibold mb-2">💬 Catatan dari Dosen Penguji:</h4>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap"><?php echo e($ujianTA->catatan_penguji); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <?php if(!empty($ujianTA->file_berita_acara)): ?>
                        <a href="<?php echo e(route('mahasiswa.ujian-ta.download', [$ujianTA->id_ujian ?? $ujianTA->getKey(), 'berita_acara'])); ?>" class="inline-block text-sm text-blue-600 hover:underline">
                            <i class="fas fa-download mr-1"></i>Download Berita Acara Ujian
                        </a>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-lg shadow p-4 mt-4">
                    <h4 class="font-semibold mb-3">Ringkasan Hasil</h4>
                    <div class="text-sm text-gray-700 grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Status Ujian</div>
                            <div class="font-semibold"><?php echo e(ucwords(str_replace('_', ' ', $ujianTA->status_ujian ?? '-'))); ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Tanggal Ujian</div>
                            <div class="font-semibold"><?php echo e($ujianTA->tanggal_ujian?->format('d M Y') ?? '-'); ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Nilai Akhir</div>
                            <div class="font-semibold"><?php echo e($ujianTA->hasil_akhir ?? $ujianTA->nilai_akhir ?? '-'); ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Catatan</div>
                            <div class="font-semibold"><?php echo e($ujianTA->catatan_penguj ? 'Ada catatan' : 'Tidak ada catatan'); ?></div>
                        </div>
                    </div>
                </div>

                
                <?php if(strpos($statusUjian, 'revisi') !== false): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-semibold mb-3">Upload Revisi Pasca Ujian</h3>
                        <form action="<?php echo e(route('mahasiswa.ujian-ta.submit-revisi')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi Naskah/Karya</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                    <p class="text-sm text-gray-500 mb-2">PDF, DOC, DOCX (Max 50MB)</p>
                                    <input type="file" name="file_revisi" class="mx-auto" required />
                                </div>
                                <?php $__errorArgs = ['file_revisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-600 text-sm mt-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Revisi</label>
                                <textarea name="deskripsi_revisi" rows="5" class="w-full border rounded p-3 text-sm" placeholder="Jelaskan perbaikan yang dilakukan..."><?php echo e(old('deskripsi_revisi')); ?></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit Revisi</button>
                            </div>
                        </form>
                    </div>
                <?php elseif(strpos($statusUjian, 'lulus') !== false): ?>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="text-center">
                            <i class="fas fa-check-circle text-green-600 text-4xl mb-3 block"></i>
                            <h3 class="font-semibold text-green-800 mb-2">Selamat Lulus!</h3>
                            <p class="text-sm text-gray-700">Ujian Anda telah dinyatakan lulus. Silakan download berita acara dan dokumen resmi lainnya.</p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h4 class="font-semibold mb-3">Status Revisi:</h4>
                <?php
                    $statusRevisi = strtolower(str_replace([' ', '-'], '_', $ujianTA->status_revisi ?? ''));
                ?>
                <div class="px-3 py-4 rounded 
                    <?php if(strpos($statusRevisi, 'selesai') !== false): ?> bg-green-50
                    <?php elseif(strpos($statusRevisi, 'persetujuan') !== false || strpos($statusRevisi, 'menunggu') !== false): ?> bg-yellow-50
                    <?php else: ?> bg-gray-50 <?php endif; ?>">
                    <div class="font-semibold 
                        <?php if(strpos($statusRevisi, 'selesai') !== false): ?> text-green-800
                        <?php elseif(strpos($statusRevisi, 'persetujuan') !== false || strpos($statusRevisi, 'menunggu') !== false): ?> text-yellow-800
                        <?php else: ?> text-gray-800 <?php endif; ?>">
                        <?php echo e(ucwords(str_replace('_', ' ', $ujianTA->status_revisi ?? 'Tidak ada revisi'))); ?>

                    </div>
                    <div class="text-sm text-gray-600 mt-2">
                        <?php if(strpos($statusRevisi, 'selesai') !== false): ?>
                            Revisi Anda telah disetujui. Hasil akhir sudah finalisasi.
                        <?php elseif(strpos($statusRevisi, 'persetujuan') !== false || strpos($statusRevisi, 'menunggu') !== false): ?>
                            Revisi Anda sedang direview oleh dosen pembimbing.
                        <?php else: ?>
                            Belum ada revisi yang disubmit.
                        <?php endif; ?>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/ujian-result.blade.php ENDPATH**/ ?>
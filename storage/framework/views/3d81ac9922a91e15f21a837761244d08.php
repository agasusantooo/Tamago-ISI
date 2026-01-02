<?php $__env->startSection('page-title', 'Progress Tugas Akhir'); ?>

<?php $__env->startSection('title', 'Ujian TA'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main column -->
        <div class="lg:col-span-8">
            <!-- Informasi Ujian Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Ujian</h2>
                <p class="text-sm text-gray-600 mb-4">Kelola dan upload file produksi tugas akhir Anda</p>

                
                <?php if(!empty($missingProposal)): ?>
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <div class="font-semibold text-red-700">Proposal belum disetujui</div>
                        <div class="text-sm text-gray-700 mt-1">Anda perlu mempunyai proposal yang disetujui terlebih dahulu sebelum dapat mendaftar ujian TA.</div>
                        <div class="mt-3">
                            <a href="<?php echo e(route('mahasiswa.proposal.index')); ?>" class="inline-block px-4 py-2 bg-red-600 text-white rounded">Lihat Proposal</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!empty($produksiNotApproved)): ?>
                    <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <div class="font-semibold text-yellow-800">Produksi akhir belum disetujui</div>
                        <div class="text-sm text-gray-700 mt-1">Produksi akhir Anda harus disetujui oleh dosen sebelum dapat mendaftar ujian.</div>
                        <div class="mt-3">
                            <a href="<?php echo e(route('mahasiswa.produksi.index')); ?>" class="inline-block px-4 py-2 bg-yellow-600 text-white rounded">Periksa Produksi</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="text-xs text-gray-600">Tanggal Ujian</div>
                        <div class="font-bold mt-2">15 Januari 2025</div>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="text-xs text-gray-600">Waktu</div>
                        <div class="font-bold mt-2">09:00 - 11:00 WIB</div>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-4">
                        <div class="text-xs text-gray-600">Ruangan</div>
                        <div class="font-bold mt-2">Lab Multimedia A</div>
                    </div>
                </div>

                <div class="mt-2">
                    <div class="text-sm text-gray-600 mb-2">Daftar Dosen Penguji</div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="font-semibold">Dr. Ahmad Santoso, M.Kom</div>
                            <div class="text-xs text-gray-600">Ketua Penguji</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="font-semibold">Prof. Dr. Sari Indah, M.T</div>
                            <div class="text-xs text-gray-600">Penguji Ahli</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Ujian Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Pengajuan Ujian</h3>

                <!-- Show existing registration status if present -->
                <?php if(!empty($ujianTA)): ?>
                    <div class="mb-4 p-4 border rounded bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-600">Berkas Tersimpan</div>
                                <div class="text-sm text-gray-500">Anda telah mengunggah berkas untuk pendaftaran ujian. Detail pendaftaran ditampilkan pada panel "Status Pengajuan" di sebelah kanan.</div>
                            </div>
                            <div class="text-right">
                                <?php if(!empty($ujianTA->file_surat_pengantar)): ?>
                                    <a href="<?php echo e(route('mahasiswa.ujian-ta.download', [$ujianTA->id_ujian ?? $ujianTA->getKey(), 'surat'])); ?>" class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded">Download Surat</a>
                                <?php endif; ?>
                                <?php if(!empty($ujianTA->file_transkrip_nilai)): ?>
                                    <a href="<?php echo e(route('mahasiswa.ujian-ta.download', [$ujianTA->id_ujian ?? $ujianTA->getKey(), 'transkrip'])); ?>" class="inline-block px-3 py-1 ml-2 bg-blue-100 text-blue-800 rounded">Download Transkrip</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <div class="text-sm text-gray-600">Persyaratan Ujian</div>
                    <ul class="list-disc list-inside text-sm text-gray-700 mt-2">
                        <li>Sudah menyetujui semua tahapan produksi</li>
                        <li>Sudah upload draf naskah</li>
                        <li>SKS lulus (minimal C) adalah minimal 138 sks</li>
                    </ul>
                </div>

                <?php
                    // determine whether to allow submission: if there's an existing active registration, block the form
                    $blockSubmission = false;
                    if (!empty($ujianTA)) {
                        $blockedStatuses = ['pengajuan_ujian', 'jadwal_ditetapkan', 'ujian_berlangsung'];
                        if (in_array($ujianTA->status_pendaftaran, $blockedStatuses)) {
                            $blockSubmission = true;
                        }
                    }
                ?>

                <form action="<?php echo e(route('mahasiswa.ujian-ta.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Surat Pengantar</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF, DOC (Max 5MB)</p>
                                <input type="file" name="file_surat_pengantar" class="mx-auto" <?php echo e($blockSubmission ? 'disabled' : ''); ?> />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip Nilai</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF (Max 5MB)</p>
                                <input type="file" name="file_transkrip_nilai" class="mx-auto" <?php echo e($blockSubmission ? 'disabled' : ''); ?> />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <?php if($blockSubmission): ?>
                            <button type="button" disabled class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg">Anda sudah terdaftar ujian TA</button>
                        <?php else: ?>
                            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Ajukan Ujian</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

    <!-- Right column -->
    <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <?php
                    $initialStatus = null;
                    if (!empty($ujianTA)) {
                        $stat = strtolower(str_replace([' ', '-', '_'], '', $ujianTA->status_pendaftaran ?? ''));
                        $variant = strpos($stat, 'pengajuan') !== false ? 'yellow' : 'green';
                        $initialStatus = ['text' => 'Status: '.str_replace('_',' ', ucfirst($ujianTA->status_pendaftaran ?? 'Tidak ada status')), 'variant' => $variant];
                        $initialTanggal = $ujianTA->tanggal_daftar ? $ujianTA->tanggal_daftar->format('d M Y') : '—';
                    } else {
                        $initialTanggal = '—';
                    }
                ?>

                <?php if(!empty($ujianTA)): ?>
                    
                    <?php
                        $timelineItems = [];
                        $timelineItems[] = ['title' => 'Pengajuan Ujian', 'date' => ($ujianTA->tanggal_daftar ? $ujianTA->tanggal_daftar->format('d M Y') : '—'), 'color' => 'green'];
                        $statusPendaftaran = strtolower(str_replace([' ', '-', '_'], '', $ujianTA->status_pendaftaran ?? ''));
                        if (strpos($statusPendaftaran, 'jadwal') !== false || $ujianTA->tanggal_ujian) {
                            $timelineItems[] = ['title' => 'Jadwal Ditetapkan', 'date' => ($ujianTA->tanggal_ujian ? $ujianTA->tanggal_ujian->format('d M Y') : '—'), 'color' => 'green'];
                        }
                        if (strpos($statusPendaftaran, 'ujianberlangsung') !== false || strpos(strtolower($ujianTA->status_ujian ?? ''), 'berlangsung') !== false || strpos(strtolower($ujianTA->status_ujian ?? ''), 'selesai') !== false) {
                            $timelineItems[] = ['title' => 'Ujian Berlangsung', 'date' => ($ujianTA->tanggal_ujian ? $ujianTA->tanggal_ujian->format('d M Y') : '—'), 'color' => (strpos(strtolower($ujianTA->status_ujian ?? ''), 'selesai') !== false ? 'green' : 'blue')];
                        }
                        $timelineItems[] = ['title' => 'Revisi Selesai', 'date' => (strpos(strtolower($ujianTA->status_revisi ?? ''), 'selesai') !== false) ? ($ujianTA->tanggal_approve_revisi?->format('d M Y') ?? '—') : 'Pending', 'color' => (strpos(strtolower($ujianTA->status_revisi ?? ''), 'selesai') !== false ? 'green' : 'gray')];

                        $pendaftaranMap = [
                            'pengajuan_ujian' => ['label' => 'Pengajuan Ujian', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                            'jadwal_ditetapkan' => ['label' => 'Jadwal Ditetapkan', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                            'ujian_berlangsung' => ['label' => 'Ujian Berlangsung', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                            'belum_ujian' => ['label' => 'Belum Ujian', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
                            'selesai_ujian' => ['label' => 'Selesai Ujian', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
                        ];

                        $key = $ujianTA->status_pendaftaran ?? null;
                        $currentPendaftaran = $key && isset($pendaftaranMap[$key]) ? $pendaftaranMap[$key] : ['label' => ucfirst(str_replace('_',' ', $key ?? 'Tidak ada status')), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                    ?>

                    <div class="bg-white rounded-lg shadow p-4 mb-6">
                        <h4 class="font-semibold mb-3">Timeline Ujian</h4>
                        <?php if(count($timelineItems) > 0): ?>
                            <ul class="space-y-3 text-sm text-gray-700">
                                <?php $__currentLoopData = $timelineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $dotClass = ($item['color'] === 'green') ? 'bg-green-500' : (($item['color'] === 'blue') ? 'bg-blue-500' : 'bg-gray-300'); ?>
                                    <li class="flex items-start">
                                        <span class="w-3 h-3 <?php echo e($dotClass); ?> rounded-full mr-3 mt-1"></span>
                                        <div>
                                            <div class="font-semibold"><?php echo e($item['title']); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($item['date']); ?></div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
                        <?php endif; ?>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="font-semibold mb-3">Status Pengajuan</h4>
                        <div class="px-3 py-4 rounded <?php echo e($currentPendaftaran['bg']); ?>">
                            <div class="font-semibold <?php echo e($currentPendaftaran['text']); ?>"><?php echo e($currentPendaftaran['label']); ?></div>
                            <div class="text-sm text-gray-600 mt-2">Setelah mengajukan, pengajuan Anda akan diverifikasi oleh admin dalam 1-3 hari kerja.</div>
                        </div>
                        <div class="mt-4 text-center">
                            <span class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">Hasil akan tersedia setelah ujian selesai</span>
                        </div>
                    </div>
                <?php else: ?>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('mahasiswa.ujian-timeline', [
                        'projekId' => optional($projek)->id_proyek_akhir ?? null,
                        'ujianId' => optional($ujianTA)->id_ujian ?? optional($ujianTA)->getKey() ?? null,
                        'status' => $initialStatus,
                        'ujianStatusPendaftaran' => optional($ujianTA)->status_pendaftaran ?? null,
                        'ujianStatus' => optional($ujianTA)->status_ujian ?? null,
                        'ujianTanggalDaftar' => $initialTanggal,
                    ]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1474918660-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <?php endif; ?>

                <?php if(session('ujian_registered')): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Livewire) {
                                Livewire.emit('ujianRegistered');
                            }
                        });
                    </script>
                <?php endif; ?>
            </div>

            
        </div>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/ujian-ta.blade.php ENDPATH**/ ?>
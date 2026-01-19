<?php $__env->startSection('title', 'Naskah & Karya - Tamago ISI'); ?>
<?php $__env->startSection('page-title', 'Progress Tugas Akhir'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto">
        
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-1"></i>
                    <div>
                        <p class="font-semibold text-red-800">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-red-700 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Main Content Wrapper -->
                    <div class="max-w-7xl mx-auto">
                        <!-- Tahapan Naskah & Karya Section -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2">Naskah & Karya Final</h2>
                            <p class="text-sm text-gray-600">Upload naskah publikasi dan file karya final yang telah disetujui untuk finalisasi tugas akhir</p>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- main column -->
                                <div class="lg:col-span-2 space-y-6">


                                    <!-- Upload Naskah Publikasi -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="font-bold text-gray-800 mb-2">Upload Naskah Publikasi</h3>
                                        <p class="text-xs text-gray-500 mb-4">Upload file naskah publikasi dalam format PDF atau DOC. Jika sudah dipublikasikan, cantumkan link jurnal di bawah.</p>

                                        <form method="POST" action="<?php echo e(route('mahasiswa.naskah-karya.upload')); ?>" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-400 transition cursor-pointer" onclick="document.getElementById('fileNaskah').click()">
                                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-sm text-gray-600 mb-1">Drop file di sini atau klik untuk upload</p>
                                                <button type="button" onclick="document.getElementById('fileNaskah').click()" class="mt-2 px-6 py-2 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700">Pilih File</button>
                                                <p class="text-xs text-gray-500 mt-2">Maksimum 50MB. PDF, DOC, DOCX, ZIP</p>
                                                <input type="file" id="fileNaskah" name="file_naskah" accept=".pdf,.doc,.docx,.zip" class="hidden" onchange="updateFileName(this, 'naskahFileName')">
                                                <p id="naskahFileName" class="text-sm text-yellow-600 font-medium mt-2"></p>
                                            </div>

                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700">Link Jurnal (opsional)</label>
                                                <input type="url" name="link_jurnal" value="<?php echo e(old('link_jurnal', optional($projek)->link_jurnal)); ?>" placeholder="https://journal.example.com/article/123" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 text-sm" />
                                            </div>

                                            <?php if(optional($projek)->file_naskah_publikasi && \Illuminate\Support\Facades\Storage::disk('public')->exists(optional($projek)->file_naskah_publikasi)): ?>
                                                <div id="naskahPublishedBox" data-projek-id="<?php echo e($projek->id_proyek_akhir ?? $projek->id); ?>" class="mt-3 bg-yellow-50 border border-yellow-200 rounded p-3">
                                                    <p class="text-sm text-yellow-800 mb-1"><i class="fas fa-check-circle mr-1"></i> Naskah sudah diunggah</p>
                                                    <p class="text-xs text-gray-500 mb-1">File: <strong><?php echo e(basename(optional($projek)->file_naskah_publikasi)); ?></strong></p>
                                                    <?php if(optional($projek)->tanggal_upload_naskah): ?>
                                                        <p class="text-xs text-gray-500 mb-1">Diunggah: <?php echo e(\Carbon\Carbon::parse($projek->tanggal_upload_naskah)->format('d M Y H:i')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="flex justify-end mt-4">
                                                <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700">Upload Naskah</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Upload Karya Final (use ProduksiController route) -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="font-bold text-gray-800 mb-2">Upload Karya Final</h3>
                                        <p class="text-xs text-gray-500 mb-4">Upload file karya final (Video/PDF/ZIP). Karya akhir hanya bisa diunggah jika pra produksi disetujui.</p>

                                        <?php if(optional($produksi)->status_pra_produksi === 'disetujui'): ?>
                                            <form method="POST" action="<?php echo e(route('mahasiswa.produksi.store.produksi')); ?>" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-yellow-400 transition cursor-pointer" onclick="document.getElementById('fileKarya').click()">
                                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                                    <p class="text-sm text-gray-600 mb-1">Drop file di sini atau klik untuk upload</p>
                                                    <p class="text-xs text-gray-500 mt-2">Maksimum 500MB - MP4/MOV/PDF/ZIP</p>
                                                    <input type="file" id="fileKarya" name="file_produksi" accept=".mp4,.mov,.avi,.mkv,.pdf,.zip" class="hidden" onchange="updateFileName(this, 'karyaFileName')">
                                                    <button type="button" onclick="document.getElementById('fileKarya').click()" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Pilih File</button>
                                                    <p id="karyaFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                                </div>

                                                <?php if(optional($produksi)->file_produksi && \Illuminate\Support\Facades\Storage::disk('public')->exists(optional($produksi)->file_produksi)): ?>
                                                    <div id="produksiFileBox" data-produksi-id="<?php echo e($produksi->id); ?>" class="mt-3 bg-green-50 border border-green-200 rounded p-3">
                                                        <p class="text-xs text-green-800 mb-1"><i class="fas fa-check-circle mr-1"></i> File sudah diunggah</p>
                                                        <p class="text-xs text-gray-500 mb-1">File: <strong><?php echo e(basename(optional($produksi)->file_produksi)); ?></strong></p>
                                                        <?php if(optional($produksi)->tanggal_upload_produksi): ?>
                                                            <p class="text-xs text-gray-500 mb-1">Diunggah: <?php echo e(\Carbon\Carbon::parse($produksi->tanggal_upload_produksi)->format('d M Y H:i')); ?></p>
                                                        <?php endif; ?>
                                                    <p class="text-xs text-gray-500 mt-2">Maksimum 500MB - MP4/MOV/AVI/MKV/PDF/ZIP</p>
                                                    <input type="file" id="fileKarya" name="file_produksi_akhir" accept=".mp4,.mov,.avi,.mkv,.pdf,.zip" class="hidden" onchange="updateFileName(this, 'karyaFileName')">
                                                    <button type="button" onclick="document.getElementById('fileKarya').click()" class="mt-3 px-6 py-2 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700">Pilih File</button>
                                                    <p id="karyaFileName" class="text-sm text-yellow-600 font-medium mt-2"></p>
                                                </div>
                                                <?php endif; ?>

                                                    <?php if(optional($produksi)->file_produksi_akhir && \Illuminate\Support\Facades\Storage::disk('public')->exists(optional($produksi)->file_produksi_akhir)): ?>
                                                    <div id="produksiFileAkhirBox" data-produksi-id="<?php echo e($produksi->id); ?>" data-type="akhir" class="mt-3 bg-yellow-50 border border-yellow-200 rounded p-3">
                                                        <p class="text-xs text-yellow-800 mb-1"><i class="fas fa-check-circle mr-1"></i> File sudah diunggah</p>
                                                        <p class="text-xs text-gray-500 mb-1">File: <strong><?php echo e(basename(optional($produksi)->file_produksi_akhir ?: optional($produksi)->file_produksi)); ?></strong></p>
                                                        <?php if(optional($produksi)->tanggal_upload_produksi): ?>
                                                            <p class="text-xs text-gray-500 mb-1">Diunggah: <?php echo e(\Carbon\Carbon::parse(optional($produksi)->tanggal_upload_produksi)->format('d M Y H:i')); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="flex justify-end mt-4">
                                                    <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700">Upload Karya</button>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="text-center py-8">
                                                <i class="fas fa-lock text-4xl text-gray-300 mb-3"></i>
                                                <p class="text-sm text-gray-600">Pra produksi harus disetujui terlebih dahulu</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- File Tambahan -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="font-bold text-gray-800 mb-2">File Tambahan</h3>
                                        <p class="text-xs text-gray-500 mb-4">Upload artbook, poster, teaser, atau infografis (opsional)</p>

                                        <?php if(optional($produksi)->status_pra_produksi === 'disetujui'): ?>
                                            <form method="POST" action="<?php echo e(route('mahasiswa.produksi.luaran-tambahan')); ?>" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Artbook</label>
                                                        <input type="file" name="file_luaran_tambahan" accept=".pdf,.jpg,.jpeg,.png,.zip" class="mt-2" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Poster</label>
                                                        <input type="file" name="file_luaran_tambahan" accept=".pdf,.jpg,.jpeg,.png,.zip" class="mt-2" />
                                                    </div>
                                                </div>
                                                <div class="flex justify-end mt-4">
                                                    <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700">Upload</button>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="text-center py-6 text-sm text-gray-500">Luaran tambahan tersedia setelah pra produksi disetujui</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- right column -->
                                <div>
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="font-bold text-gray-800 mb-4">Alur Proses Finalisasi</h3>
                                        <div class="space-y-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-check text-yellow-600"></i></div>
                                                <div>
                                                    <p class="font-semibold">Pembimbingan</p>
                                                    <p class="text-xs text-gray-500">Selesai</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-check text-yellow-600"></i></div>
                                                <div>
                                                    <p class="font-semibold">Pengesahan</p>
                                                    <p class="text-xs text-gray-500">Selesai</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-cloud-upload-alt text-yellow-600"></i></div>
                                                <div>
                                                    <p class="font-semibold">Upload Final</p>
                                                    <p class="text-xs text-gray-500">Sedang Berlangsung</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3"><i class="fas fa-hourglass text-gray-500"></i></div>
                                                <div>
                                                    <p class="font-semibold">Selesai</p>
                                                    <p class="text-xs text-gray-500">Menunggu</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex justify-between">
                                            <button type="button" onclick="saveDraft()" class="px-4 py-2 bg-white border rounded hover:bg-gray-50 transition">Simpan Draft</button>
                                            <button type="button" onclick="submitVerification()" class="px-4 py-2 bg-yellow-400 text-black rounded hover:bg-yellow-500 transition">Ajukan Verifikasi</button>
                                        </div>
                                    </div>

                                    <!-- status box (placeholder) -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Pop-up -->
                <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 p-8 animate-fadeIn">
                        <div class="text-center">
                            <div id="modalIcon" class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"></div>
                            <h2 id="modalTitle" class="text-xl font-bold text-gray-800 mb-2"></h2>
                            <p id="modalMessage" class="text-gray-600 mb-6"></p>
                        </div>
                        <div class="flex gap-3 justify-center">
                            <button id="modalBtn1" type="button" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium" onclick="closeModal()">Tutup</button>
                            <button id="modalBtn2" type="button" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium hidden" onclick="confirmAction()"></button>
                        </div>
                    </div>
                </div>

                <script>
                    let currentAction = null;

                    function updateFileName(input, displayId) {
                        const file = input.files[0];
                        const display = document.getElementById(displayId);
                        if (file) {
                            display.textContent = `✓ ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        }
                    }

                    function showModal(title, message, icon, action = null, hasConfirmBtn = false, confirmBtnText = 'Konfirmasi') {
                        const modal = document.getElementById('modal');
                        const modalIcon = document.getElementById('modalIcon');
                        const modalTitle = document.getElementById('modalTitle');
                        const modalMessage = document.getElementById('modalMessage');
                        const modalBtn2 = document.getElementById('modalBtn2');

                        modalTitle.textContent = title;
                        modalMessage.textContent = message;
                        modalIcon.className = icon;
                        currentAction = action;

                        if (hasConfirmBtn) {
                            modalBtn2.textContent = confirmBtnText;
                            modalBtn2.classList.remove('hidden');
                        } else {
                            modalBtn2.classList.add('hidden');
                        }

                        modal.classList.remove('hidden');
                    }

                    function closeModal() {
                        const modal = document.getElementById('modal');
                        modal.classList.add('hidden');
                        currentAction = null;
                    }

                    function confirmAction() {
                        if (currentAction === 'verify') {
                            closeModal();
                            showModal(
                                '✓ Berhasil!',
                                'Verifikasi telah diajukan. Silakan tunggu review dari koordinator.',
                                'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-yellow-100',
                                null,
                                false
                            );
                            // TODO: Kirim data ke backend
                        }
                    }

                    function saveDraft() {
                        showModal(
                            'Simpan Draft',
                            'Apakah Anda yakin ingin menyimpan draft? File akan tersimpan dalam sistem.',
                            'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-yellow-100',
                            'draft',
                            false
                        );
                    }

                    function submitVerification() {
                        showModal(
                            'Ajukan Verifikasi',
                            'Pastikan semua file sudah diunggah dengan benar. Apakah Anda yakin ingin mengajukan verifikasi?',
                            'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-yellow-100',
                            'verify',
                            true,
                            'Ya, Ajukan'
                        );
                    }
                </script>

                <style>
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: scale(0.95);
                        }
                        to {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }
                    .animate-fadeIn {
                        animation: fadeIn 0.3s ease-in-out;
                    }
                </style>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function isHiddenNaskah(id) {
        return localStorage.getItem(`hidden_naskah_${id}`) === '1';
    }
    function isHiddenProduksi(id, type) {
        return localStorage.getItem(`hidden_produksi_${id}_${type}`) === '1';
    }
    function hideNaskah(id) {
        localStorage.setItem(`hidden_naskah_${id}`, '1');
        const el = document.querySelector(`#naskahPublishedBox[data-projek-id="${id}"]`);
        if (el) el.remove();
    }
    function hideProduksi(id, type) {
        localStorage.setItem(`hidden_produksi_${id}_${type}`, '1');
        const selector = type === 'akhir' ? `#produksiFileAkhirBox[data-produksi-id="${id}"]` : `#produksiFileBox[data-produksi-id="${id}"]`;
        const el = document.querySelector(selector);
        if (el) el.remove();
    }

    async function fetchNaskahUpdates(){
        try {
            const res = await fetch("<?php echo e(route('mahasiswa.naskah-karya.check-updates')); ?>", { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const json = await res.json();
            if (!json.success) return;

            const projek = json.projek;
            const produksi = json.produksi;

            const naskahBox = document.getElementById('naskahPublishedBox');
            if (naskahBox) {
                if (projek && projek.file_naskah_publikasi && !isHiddenNaskah(projek.id)) {
                    const fileName = projek.file_naskah_publikasi.split('/').pop();
                    naskahBox.dataset.projekId = projek.id;
                    naskahBox.innerHTML = `<p class="text-sm text-yellow-800 mb-1"><i class="fas fa-check-circle mr-1"></i> Naskah sudah diunggah</p><p class="text-xs text-gray-500 mb-1">File: <strong>${fileName}</strong></p>`;
                } else {
                    naskahBox.innerHTML = '';
                }
            }

            const produksiBox = document.getElementById('produksiFileBox');
            const produksiAkhirBox = document.getElementById('produksiFileAkhirBox');

            if (produksiBox) {
                if (produksi && produksi.file_produksi && !isHiddenProduksi(produksi.id, 'produksi')) {
                    const fileName = produksi.file_produksi.split('/').pop();
                    produksiBox.dataset.produksiId = produksi.id;
                    produksiBox.innerHTML = `<p class="text-xs text-green-800 mb-1"><i class="fas fa-check-circle mr-1"></i> File sudah diunggah</p><p class="text-xs text-gray-500 mb-1">File: <strong>${fileName}</strong></p>`;
                } else {
                    produksiBox.innerHTML = '';
                }
            }
            if (produksiAkhirBox) {
                if (produksi && produksi.file_produksi_akhir && !isHiddenProduksi(produksi.id, 'akhir')) {
                    const fileName = (produksi.file_produksi_akhir || produksi.file_produksi).split('/').pop();
                    produksiAkhirBox.dataset.produksiId = produksi.id;
                    produksiAkhirBox.dataset.type = 'akhir';
                    produksiAkhirBox.innerHTML = `<p class="text-xs text-yellow-800 mb-1"><i class="fas fa-check-circle mr-1"></i> File akhir sudah diunggah</p><p class="text-xs text-gray-500 mb-1">File: <strong>${fileName}</strong></p>`;
                } else {
                    produksiAkhirBox.innerHTML = '';
                }
            }
        } catch (e) { console.error('Failed to fetch naskah updates', e); }
    }

    function removeHiddenBoxesOnLoad() {
        // remove server-rendered boxes if hidden flags are present
        document.querySelectorAll('[data-projek-id]').forEach(el => {
            const id = el.dataset.projekId;
            if (id && isHiddenNaskah(id)) el.remove();
        });
        document.querySelectorAll('[data-produksi-id]').forEach(el => {
            const id = el.dataset.produksiId;
            const type = el.dataset.type || (el.id === 'produksiFileBox' ? 'produksi' : 'akhir');
            if (id && isHiddenProduksi(id, type)) el.remove();
        });
    }

    document.addEventListener('DOMContentLoaded', function(){ fetchNaskahUpdates(); removeHiddenBoxesOnLoad(); setInterval(fetchNaskahUpdates, 15000); });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/naskah-karya.blade.php ENDPATH**/ ?>
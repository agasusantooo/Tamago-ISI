<?php $__env->startSection('title', 'Produksi - Tamago ISI'); ?>
<?php $__env->startSection('page-title', 'Produksi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto">
        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-green-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                                <p class="text-red-700"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

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
                        <!-- Tahapan Produksi Section -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2">Tahapan Produksi</h2>
                            <p class="text-sm text-gray-600">Kelola dan upload file produksi tugas akhir Anda</p>
                            <!-- Tabs -->
                            <div class="border-b">
                                <nav class="flex">
                                    <button onclick="switchTab('pra-produksi')" 
                                            id="tab-pra-produksi"
                                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                                        Pra Produksi
                                    </button>
                                    <button onclick="switchTab('produksi-akhir')" 
                                            id="tab-produksi-akhir"
                                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                                        Produksi Akhir
                                    </button>
                                </nav>
                            </div>
                            <!-- Tab Content: Pra Produksi -->
                            <div id="content-pra-produksi" class="tab-content">
                                <!-- Upload Areas -->
                                <div class="space-y-6 mb-6">
                                    <form method="POST" action="<?php echo e(route('mahasiswa.produksi.store.pra')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>

                                        <!-- File Skenario -->
                                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                                            <h3 class="font-bold text-gray-800 mb-4">File Skenario</h3>
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer" 
                                                onclick="document.getElementById('fileSkenario').click()">
                                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-sm text-gray-600 mb-1">Drag & drop file skenario atau</p>
                                                <button type="button" onclick="document.getElementById('fileSkenario').click()"
                                                    class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                                    Pilih File
                                                </button>
                                                <p class="text-xs text-gray-500 mt-2">Maksimal 10MB</p>
                                                <input type="file" id="fileSkenario" name="file_skenario" 
                                                    accept=".pdf,.doc,.docx" 
                                                    class="hidden" 
                                                    onchange="updateFileName(this, 'skenarioFileName')">
                                                <p id="skenarioFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                            </div>
                                            <?php if($produksi?->file_skenario): ?>
                                                <div class="mt-3 text-center">
                                                    <a href="<?php echo e(route('mahasiswa.produksi.download', [$produksi->id, 'skenario'])); ?>" 
                                                       class="text-sm text-blue-600 hover:underline">
                                                        <i class="fas fa-download mr-1"></i> Download File Skenario
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- File Story Board -->
                                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                                            <h3 class="font-bold text-gray-800 mb-4">File Story Board</h3>
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer" 
                                                onclick="document.getElementById('fileStoryboard').click()">
                                                <i class="fas fa-images text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-sm text-gray-600 mb-1">Upload storyboard sequence</p>
                                                <button type="button" onclick="document.getElementById('fileStoryboard').click()"
                                                    class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                                    Pilih File
                                                </button>
                                                <p class="text-xs text-gray-500 mt-2">Maksimal 20MB</p>
                                                <input type="file" id="fileStoryboard" name="file_storyboard" 
                                                    accept=".pdf,.jpg,.jpeg,.png" 
                                                    class="hidden" 
                                                    onchange="updateFileName(this, 'storyboardFileName')">
                                                <p id="storyboardFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                            </div>
                                            <?php if($produksi?->file_storyboard): ?>
                                                <div class="mt-3 text-center">
                                                    <a href="<?php echo e(route('mahasiswa.produksi.download', [$produksi->id, 'storyboard'])); ?>" 
                                                       class="text-sm text-blue-600 hover:underline">
                                                        <i class="fas fa-download mr-1"></i> Download File Storyboard
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Dokumen Pendukung Lain -->
                                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                                            <h3 class="font-bold text-gray-800 mb-4">Dokumen Pendukung Lain</h3>
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer" 
                                                onclick="document.getElementById('fileDokumen').click()">
                                                <i class="fas fa-file-archive text-4xl text-gray-400 mb-3"></i>
                                                <p class="text-sm text-gray-600 mb-1">Upload dokumen pendukung</p>
                                                <button type="button" onclick="document.getElementById('fileDokumen').click()"
                                                    class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                                    Pilih File
                                                </button>
                                                <p class="text-xs text-gray-500 mt-2">Maksimal 20MB</p>
                                                <input type="file" id="fileDokumen" name="file_dokumen_pendukung" 
                                                    accept=".pdf,.doc,.docx,.zip" 
                                                    class="hidden" 
                                                    onchange="updateFileName(this, 'dokumenFileName')">
                                                <p id="dokumenFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                            </div>
                                            <?php if($produksi?->file_dokumen_pendukung): ?>
                                                <div class="mt-3 text-center">
                                                    <a href="<?php echo e(route('mahasiswa.produksi.download', [$produksi->id, 'dokumen'])); ?>" 
                                                       class="text-sm text-blue-600 hover:underline">
                                                        <i class="fas fa-download mr-1"></i> Download Dokumen Pendukung
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="flex justify-end">
                                            <button type="submit" 
                                                class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                                <i class="fas fa-upload mr-2"></i>
                                                <?php echo e($produksi && $produksi->file_skenario ? 'Update' : 'Upload'); ?> File Pra Produksi
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Status Persetujuan & Feedback -->
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="font-bold text-gray-800 mb-4">Status Persetujuan</h3>
                                    
                                    <?php if($produksi): ?>
                                        <div class="mb-4">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <?php
                                                    $statusIcon = optional($produksi->statusPraProduksiBadge)['icon'] ?? 'clock';
                                                    $statusColor = 'text-yellow-600';
                                                    if($produksi->status_pra_produksi === 'disetujui') {
                                                        $statusColor = 'text-green-600';
                                                    } elseif($produksi->status_pra_produksi === 'ditolak') {
                                                        $statusColor = 'text-red-600';
                                                    }
                                                ?>
                                                <i class="fas fa-<?php echo e($statusIcon); ?> <?php echo e($statusColor); ?>"></i>
                                                <span class="font-semibold text-gray-800">
                                                    <?php echo e(optional($produksi->statusPraProduksiBadge)['text']); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div class="border-t pt-4">
                                            <h4 class="font-semibold text-gray-800 mb-3">Catatan/Feedback Dosen Pembimbing</h4>
                                            
                                            <?php if($produksi->feedback_pra_produksi): ?>
                                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                                    <p class="text-sm text-gray-700 leading-relaxed">
                                                        <?php echo e($produksi->feedback_pra_produksi); ?>

                                                    </p>
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                                    <p class="text-sm text-gray-700">Belum ada Feedback</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-8">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                            <p class="text-sm text-gray-500">Belum ada data pra produksi</p>
                                            <p class="text-xs text-gray-400 mt-1">Upload file di atas untuk memulai</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tab Content: Produksi Akhir -->
                            <div id="content-produksi-akhir" class="tab-content hidden">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                    <!-- File Karya Final -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-video text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">File Karya Final</h3>
                                                <p class="text-xs text-gray-500">Video/PDF/ZIP - sesuai jenis karya</p>
                                            </div>
                                        </div>

                                        <?php if($produksi && $produksi->status_pra_produksi === 'disetujui'): ?>
                                            <form method="POST" action="<?php echo e(route('mahasiswa.produksi.produksi-akhir')); ?>" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition cursor-pointer mb-4" 
                                                    onclick="document.getElementById('fileKaryaFinal').click()">
                                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                                    <p class="text-sm text-gray-600 mb-1">Drag & drop file atau klik untuk upload</p>
                                                    <p class="text-xs text-gray-500">Maksimal 500MB - Video, PDF, ZIP</p>
                                                    <input type="file" id="fileKaryaFinal" name="file_produksi_akhir" 
                                                        accept=".mp4,.mov,.avi,.mkv,.pdf,.zip" 
                                                        class="hidden" 
                                                        onchange="updateFileName(this, 'karyaFinalFileName')">
                                                    <button type="button" onclick="document.getElementById('fileKaryaFinal').click()"
                                                        class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                                        Pilih File
                                                    </button>
                                                    <p id="karyaFinalFileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                                                </div>
                                                
                                                <?php if($produksi?->file_produksi_akhir): ?>
                                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                                        <p class="text-xs text-green-800 mb-1">
                                                            <i class="fas fa-check-circle mr-1"></i> File sudah diunggah
                                                        </p>
                                                        <a href="<?php echo e(route('mahasiswa.produksi.download', [$produksi->id, 'akhir'])); ?>" 
                                                           class="text-sm text-blue-600 hover:underline">
                                                            <i class="fas fa-download mr-1"></i> Download File
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <button type="submit" 
                                                    class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition text-sm">
                                                    <i class="fas fa-upload mr-2"></i>Upload File
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <div class="text-center py-12">
                                                <i class="fas fa-lock text-5xl text-gray-300 mb-4"></i>
                                                <p class="text-gray-600 font-medium mb-2">Pra Produksi harus disetujui terlebih dahulu</p>
                                                <p class="text-sm text-gray-500">Mohon lengkapi dan tunggu persetujuan pra produksi</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- File Luaran Tambahan -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-images text-purple-600"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">File Luaran Tambahan</h3>
                                                <p class="text-xs text-gray-500">Artbook/Poster/Teaser/Infografis</p>
                                            </div>
                                        </div>

                                        <?php if($produksi && $produksi->status_pra_produksi === 'disetujui'): ?>
                                            <form method="POST" action="<?php echo e(route('mahasiswa.produksi.luaran-tambahan')); ?>" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition cursor-pointer mb-4" 
                                                    onclick="document.getElementById('fileLuaranTambahan').click()">
                                                    <i class="fas fa-plus-circle text-4xl text-gray-400 mb-3"></i>
                                                    <p class="text-sm text-gray-600 mb-1">Upload file pendukung (opsional)</p>
                                                    <p class="text-xs text-gray-500">PDF, JPG, PNG, ZIP</p>
                                                    <input type="file" id="fileLuaranTambahan" name="file_luaran_tambahan" 
                                                        accept=".pdf,.jpg,.jpeg,.png,.zip" 
                                                        class="hidden" 
                                                        onchange="updateFileName(this, 'luaranTambahanFileName')">
                                                    <button type="button" onclick="document.getElementById('fileLuaranTambahan').click()"
                                                        class="mt-3 px-6 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700">
                                                        Pilih File
                                                    </button>
                                                    <p id="luaranTambahanFileName" class="text-sm text-purple-600 font-medium mt-2"></p>
                                                </div>

                                                <?php if($produksi?->file_luaran_tambahan): ?>
                                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                                        <p class="text-xs text-green-800 mb-1">
                                                            <i class="fas fa-check-circle mr-1"></i> File sudah diunggah
                                                        </p>
                                                        <a href="<?php echo e(route('mahasiswa.produksi.download', [$produksi->id, 'luaran'])); ?>" 
                                                           class="text-sm text-blue-600 hover:underline">
                                                            <i class="fas fa-download mr-1"></i> Download File
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <button type="submit" 
                                                    class="w-full bg-purple-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-purple-700 transition text-sm">
                                                    <i class="fas fa-upload mr-2"></i>Upload File
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <div class="text-center py-12">
                                                <i class="fas fa-lock text-4xl text-gray-300 mb-3"></i>
                                                <p class="text-sm text-gray-500">Tersedia setelah pra produksi disetujui</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Status & Feedback Section -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Status Persetujuan -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="font-bold text-gray-800 mb-4">Status Persetujuan</h3>
                                        <?php if($produksi): ?>
                                            <div class="mb-4">
                                                <p class="text-sm font-semibold text-gray-700 mb-1">
                                                    
                                                    <?php echo e($produksi->dosen ? ($produksi->dosen->nama . ($produksi->dosen->gelar ? ', ' . $produksi->dosen->gelar : '')) : 'Dosen pembimbing belum ditetapkan'); ?>

                                                </p>
                                                <p class="text-xs text-gray-500">Dosen Pembimbing</p>
                                            </div>

                                            <div class="mb-4">
                                                <span class="px-3 py-1 <?php echo e($produksi->statusProduksiAkhirColor); ?> text-sm font-semibold rounded-full">
                                                    <?php echo e(optional($produksi->statusProduksiAkhirBadge)['text'] ?? 'Belum ada status'); ?>

                                                </span>
                                            </div>

                                            <?php if($produksi->tanggal_upload_akhir): ?>
                                                <p class="text-xs text-gray-600">
                                                    Disubmit: <?php echo e($produksi->tanggal_upload_akhir->format('d F Y, H:i')); ?>

                                                </p>
                                            <?php endif; ?>

                                            <div class="mt-4 pt-4 border-t">
                                                <p class="text-sm text-gray-600">
                                                    File sedang dalam proses review oleh dosen pembimbing
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-8">
                                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                                <p class="text-sm text-gray-500">Belum ada data produksi akhir</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Catatan/Feedback -->
                                    <div class="bg-white rounded-lg shadow-sm p-6">
                                        <h3 class="font-bold text-gray-800 mb-4">Catatan/Feedback</h3>
                                        
                                        <?php if($produksi && $produksi->feedback_produksi_akhir): ?>
                                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-user-circle text-2xl text-blue-600"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-blue-900 text-sm mb-2">
                                                            <?php echo e($produksi->dosen ? $produksi->dosen->nama : 'Dr. Sarah Wijaya'); ?>

                                                        </p>
                                                        <p class="text-sm text-gray-700 leading-relaxed">
                                                            <?php echo e($produksi->feedback_produksi_akhir); ?>

                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                                                <i class="fas fa-comment-slash text-3xl text-gray-300 mb-3"></i>
                                                <p class="text-sm text-gray-500">Belum ada feedback</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function updateFileName(input, displayId) {
                        const file = input.files[0];
                        const display = document.getElementById(displayId);
                        if (file) {
                            display.textContent = `✓ ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        }
                    }

                    function switchTab(tabName) {
                        // Hide all tab contents
                        document.querySelectorAll('.tab-content').forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Remove active state from all tabs
                        document.querySelectorAll('.tab-button').forEach(button => {
                            button.classList.remove('border-blue-600', 'text-blue-600');
                            button.classList.add('border-transparent', 'text-gray-500');
                        });

                        // Show selected tab content
                        document.getElementById('content-' + tabName).classList.remove('hidden');

                        // Add active state to selected tab
                        const selectedTab = document.getElementById('tab-' + tabName);
                        selectedTab.classList.remove('border-transparent', 'text-gray-500');
                        selectedTab.classList.add('border-blue-600', 'text-blue-600');
                    }

                    // Drag & Drop functionality
                    ['fileSkenario', 'fileStoryboard', 'fileDokumen', 'fileKaryaFinal', 'fileLuaranTambahan'].forEach(id => {
                        const element = document.getElementById(id);
                        if (!element) return;

                        const dropZone = element.parentElement;

                        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                            dropZone.addEventListener(eventName, preventDefaults, false);
                        });

                        function preventDefaults(e) {
                            e.preventDefault();
                            e.stopPropagation();
                        }

                        ['dragenter', 'dragover'].forEach(eventName => {
                            dropZone.addEventListener(eventName, () => {
                                dropZone.classList.add('border-blue-500', 'bg-blue-50');
                            }, false);
                        });

                        ['dragleave', 'drop'].forEach(eventName => {
                            dropZone.addEventListener(eventName, () => {
                                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                            }, false);
                        });

                        dropZone.addEventListener('drop', (e) => {
                            const dt = new DataTransfer();
                            Array.from(e.dataTransfer.files).forEach(file => {
                                dt.items.add(file);
                            });
                            element.files = dt.files;
                            const displayId = id + 'FileName';
                            updateFileName(element, displayId);
                        }, false);
                    });
                </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views/mahasiswa/produksi.blade.php ENDPATH**/ ?>
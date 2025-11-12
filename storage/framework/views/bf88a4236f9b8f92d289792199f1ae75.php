

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

                <!-- tombol hasil ujian dipindah ke kanan bawah status -->

                <div class="mb-4">
                    <div class="text-sm text-gray-600">Persyaratan Ujian</div>
                    <ul class="list-disc list-inside text-sm text-gray-700 mt-2">
                        <li>Sudah menyetujui semua tahapan produksi</li>
                        <li>Sudah upload draf naskah</li>
                        <li>SKS lulus (minimal C) adalah minimal 138 sks</li>
                    </ul>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Surat Pengantar</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF, DOC (Max 5MB)</p>
                                <input type="file" name="file_surat_pengantar" class="mx-auto" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Transkrip Nilai</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF (Max 5MB)</p>
                                <input type="file" name="file_transkrip" class="mx-auto" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Ajukan Ujian</button>
                    </div>
                </form>
            </div>
        </div>

    <!-- Right column -->
    <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h4 class="font-semibold mb-3">Timeline Ujian</h4>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-3 mt-1"></span>
                        <div>
                            <div class="font-semibold">Pengajuan Ujian</div>
                            <div class="text-xs text-gray-500">10 Jan 2024</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-3 mt-1"></span>
                        <div>
                            <div class="font-semibold">Jadwal Ditetapkan</div>
                            <div class="text-xs text-gray-500">12 Jan 2024</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-3 mt-1"></span>
                        <div>
                            <div class="font-semibold">Ujian Berlangsung</div>
                            <div class="text-xs text-gray-500">15 Jan 2024</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="w-3 h-3 bg-gray-300 rounded-full mr-3 mt-1"></span>
                        <div>
                            <div class="font-semibold">Revisi Selesai</div>
                            <div class="text-xs text-gray-500">Pending</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="font-semibold mb-3">Status Pengajuan</h4>
                <div class="px-3 py-4 bg-yellow-50 rounded">
                    <div class="font-semibold text-yellow-800">Status: Menunggu Persetujuan</div>
                    <div class="text-sm text-gray-600 mt-2">Setelah mengajukan, pengajuan Anda akan diverifikasi oleh admin dalam 1-3 hari kerja.</div>
                </div>
                <div class="mt-4 text-center">
                    <a href="<?php echo e(route('mahasiswa.ujian-result')); ?>" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded shadow text-sm hover:bg-indigo-700">Lihat Hasil Ujian</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tam\backk\resources\views/mahasiswa/ujian-ta.blade.php ENDPATH**/ ?>
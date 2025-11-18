

<?php $__env->startSection('title', 'Hasil Ujian TA'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Hasil Ujian Tugas Akhir</h2>
                <p class="text-sm text-gray-600 mb-4">Status dan feedback hasil ujian tugas akhir Anda</p>

                <div class="bg-yellow-50 border-l-4 border-yellow-300 rounded p-4 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="text-yellow-700 font-semibold">Status: Perlu Revisi</div>
                        <div class="text-sm text-gray-700">Ujian Anda memerlukan beberapa perbaikan sebelum dapat dinyatakan lulus. Silakan lihat catatan feedback di bawah ini.</div>
                    </div>

                    <div class="mt-4 bg-white rounded p-4 border">
                        <h4 class="font-semibold mb-2">Catatan dari Dosen Penguji:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                            <li>Perlu perbaikan pada bagian metodologi penelitian, terutama pada penjelasan validitas instrumen</li>
                            <li>Analisis data pada Bab 4 perlu diperdalam dengan interpretasi yang lebih komprehensif</li>
                            <li>Kesimpulan dan saran perlu disesuaikan dengan temuan penelitian</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-4">
                    <a href="#" class="inline-block text-sm text-blue-600 hover:underline">Download Berita Acara Ujian</a>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-3">Upload Revisi Pasca Ujian</h3>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi Naskah/Karya</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">PDF, DOC, DOCX (Max 50MB)</p>
                                <input type="file" name="file_revisi" class="mx-auto" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Revisi</label>
                            <textarea name="deskripsi_revisi" rows="5" class="w-full border rounded p-3 text-sm" placeholder="Jelaskan perbaikan yang dilakukan..."></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Submit Revisi</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h4 class="font-semibold mb-3">Status:</h4>
                <div class="px-3 py-4 bg-blue-50 rounded">
                    <div class="font-semibold text-blue-800">Menunggu Persetujuan Revisi</div>
                    <div class="text-sm text-gray-600 mt-2">Setelah submit, revisi Anda akan direview oleh dosen pembimbing dalam 3-5 hari kerja.</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h4 class="font-semibold mb-3">Ringkasan Hasil</h4>
                <div class="text-sm text-gray-700">
                    <p><strong>Nilai Akhir:</strong> -</p>
                    <p><strong>Catatan:</strong> Perlu revisi substansial</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mahasiswa.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tam\backk\resources\views/mahasiswa/ujian-result.blade.php ENDPATH**/ ?>
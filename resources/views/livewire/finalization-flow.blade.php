@php
    // ensure $produksi is defined to avoid 'Undefined variable' when blade is rendered
    $produksi = $produksi ?? null;
@endphp

<div wire:poll.5000ms class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-4">Alur Proses Finalisasi</h3>

    <div class="space-y-6">
        <div class="flex items-center">
            <div class="w-8 h-8 {{ optional($produksi)->status_pra_produksi == 'disetujui' ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-check {{ optional($produksi)->status_pra_produksi == 'disetujui' ? 'text-green-600' : 'text-gray-400' }}"></i>
            </div>
            <div>
                <p class="font-semibold">Pembimbingan</p>
                <p class="text-xs text-gray-500">{{ optional($produksi)->status_pra_produksi == 'disetujui' ? 'Selesai' : (optional($produksi)->status_pra_produksi ? ucfirst(optional($produksi)->status_pra_produksi) : 'Belum') }}</p>
            </div>
        </div>

        <div class="flex items-center">
            <div class="w-8 h-8 {{ optional($produksi)->proposal && optional($produksi->proposal)->status == 'disetujui' ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-check {{ optional($produksi)->proposal && optional($produksi->proposal)->status == 'disetujui' ? 'text-green-600' : 'text-gray-400' }}"></i>
            </div>
            <div>
                <p class="font-semibold">Pengesahan</p>
                <p class="text-xs text-gray-500">{{ optional($produksi)->proposal && optional($produksi->proposal)->status == 'disetujui' ? 'Selesai' : 'Belum' }}</p>
            </div>
        </div>

        <div class="flex items-center">
            <div class="w-8 h-8 {{ optional($produksi)->file_produksi_akhir ? 'bg-yellow-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-cloud-upload-alt {{ optional($produksi)->file_produksi_akhir ? 'text-yellow-600' : 'text-gray-400' }}"></i>
            </div>
            <div>
                <p class="font-semibold">Upload Final</p>
                <p class="text-xs text-gray-500">{{ optional($produksi)->file_produksi_akhir ? (optional($produksi)->status_produksi_akhir == 'menunggu_review' ? 'Menunggu Review' : (optional($produksi)->status_produksi_akhir == 'disetujui' ? 'Disetujui' : 'Sedang Berlangsung')) : 'Belum Diunggah' }}</p>
            </div>
        </div>

        <div class="flex items-center">
            <div class="w-8 h-8 {{ optional($produksi)->status_produksi_akhir == 'disetujui' ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-hourglass {{ optional($produksi)->status_produksi_akhir == 'disetujui' ? 'text-green-600' : 'text-gray-400' }}"></i>
            </div>
            <div>
                <p class="font-semibold">Selesai</p>
                <p class="text-xs text-gray-500">{{ optional($produksi)->status_produksi_akhir == 'disetujui' ? 'Selesai' : 'Menunggu' }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <button wire:click="saveDraft" class="px-4 py-2 bg-white border rounded">Simpan Draft</button>
        <button wire:click="submitVerification" class="px-4 py-2 bg-yellow-400 text-black rounded">Ajukan Verifikasi</button>
    </div>
</div>

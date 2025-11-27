<div>
    <!-- Konfirmasi Dialog -->
    @if($showConfirm && $confirmAction === 'approve')
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-check text-green-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        Setujui Jadwal Bimbingan?
                    </h3>
                    <p class="text-sm text-gray-600">
                        Jadwal bimbingan akan disetujui dan mahasiswa akan mendapat notifikasi.
                    </p>
                </div>

                <div class="mt-6 flex gap-3">
                    <button wire:click="cancel()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                        Batal
                    </button>
                    <button wire:click="proceed()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                        Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showConfirm && $confirmAction === 'reject')
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-times text-red-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        Tolak Jadwal Bimbingan?
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Jadwal bimbingan akan ditolak dan mahasiswa akan mendapat notifikasi.
                    </p>

                    <!-- Alasan Penolakan (Opsional) -->
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan (Opsional)
                        </label>
                        <textarea wire:model.defer="rejectReason" 
                                  placeholder="Tuliskan alasan penolakan..." 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none" 
                                  rows="3">
                        </textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Mahasiswa akan melihat alasan ini
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button wire:click="cancel()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                        Batal
                    </button>
                    <button wire:click="proceed()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

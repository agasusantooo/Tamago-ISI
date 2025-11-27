<div>
    <!-- Modal Backdrop -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-40" wire:click="closeModal()"></div>
    @endif

    <!-- Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none @if($showModal) pointer-events-auto @endif">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 @if($showModal) scale-100 opacity-100 @else scale-95 opacity-0 @endif">
            
            <!-- Close Button -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    @if($confirmAction === 'approve')
                        Setujui Jadwal Bimbingan
                    @elseif($confirmAction === 'reject')
                        Tolak Jadwal Bimbingan
                    @else
                        Detail Jadwal Bimbingan
                    @endif
                </h3>
                <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Main Content -->
            <div class="mb-6 space-y-4">
                @if($jadwal)
                    <!-- Jadwal Details -->
                    @if(!$confirmAction)
                        <div class="space-y-4">
                            <!-- Mahasiswa Info -->
                            @if($jadwal->mahasiswa)
                                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                    <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                                        <span class="font-bold text-blue-700">{{ strtoupper(substr($jadwal->mahasiswa->nama ?? 'M', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $jadwal->mahasiswa->nama ?? 'Mahasiswa' }}</p>
                                        <p class="text-xs text-gray-600">{{ $jadwal->mahasiswa->nim ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Jadwal Details -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal & Waktu</label>
                                    <p class="text-sm text-gray-900">
                                        {{ $jadwal->tanggal ? $jadwal->tanggal->format('d M Y') : '-' }} 
                                        @if($jadwal->jam_mulai)
                                            Pukul {{ date('H:i', strtotime($jadwal->jam_mulai)) }}
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tempat/Ruangan</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->tempat ?? '-' }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Topik Bimbingan</label>
                                    <p class="text-sm text-gray-900">{{ $jadwal->topik ?? '-' }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($jadwal->status === 'menunggu')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($jadwal->status === 'disetujui')
                                            bg-green-100 text-green-800
                                        @elseif($jadwal->status === 'ditolak')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($jadwal->status ?? '-') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Approve Confirmation -->
                    @if($confirmAction === 'approve')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-3xl mb-2">✓</div>
                            <p class="text-sm font-medium text-gray-900 mb-2">Apakah Anda yakin ingin menyetujui jadwal bimbingan ini?</p>
                            <p class="text-xs text-gray-600">
                                @if($jadwal->mahasiswa)
                                    dengan {{ $jadwal->mahasiswa->nama }}
                                @endif
                                @if($jadwal->tanggal)
                                    pada {{ $jadwal->tanggal->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                    @endif

                    <!-- Reject Confirmation -->
                    @if($confirmAction === 'reject')
                        <div class="space-y-4">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <div class="text-3xl mb-2">✕</div>
                                <p class="text-sm font-medium text-gray-900 mb-2">Apakah Anda yakin ingin menolak jadwal bimbingan ini?</p>
                                <p class="text-xs text-gray-600">
                                    @if($jadwal->mahasiswa)
                                        dengan {{ $jadwal->mahasiswa->nama }}
                                    @endif
                                    @if($jadwal->tanggal)
                                        pada {{ $jadwal->tanggal->format('d M Y') }}
                                    @endif
                                </p>
                            </div>

                            <!-- Alasan Penolakan -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Alasan Penolakan (Opsional)</label>
                                <textarea wire:model.defer="actionMessage" placeholder="Tuliskan alasan penolakan..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none" rows="3"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Mahasiswa akan melihat alasan ini</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-6 text-gray-500">
                        <p>Data jadwal tidak ditemukan</p>
                    </div>
                @endif
            </div>

            <!-- Modal Actions -->
            <div class="flex gap-3">
                @if(!$confirmAction)
                    <!-- Default Actions -->
                    <button wire:click="setAction('reject')" class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                    <button wire:click="setAction('approve')" class="flex-1 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>Setujui
                    </button>
                @elseif($confirmAction === 'approve')
                    <!-- Approve Confirmation Actions -->
                    <button wire:click="cancelAction()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button wire:click="approveBimbingan()" class="flex-1 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>Ya, Setujui
                    </button>
                @elseif($confirmAction === 'reject')
                    <!-- Reject Confirmation Actions -->
                    <button wire:click="cancelAction()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button wire:click="rejectBimbingan()" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Ya, Tolak
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

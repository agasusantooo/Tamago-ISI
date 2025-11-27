<!-- Modal untuk ACC/Tolak Jadwal Bimbingan -->
<div id="jadwalApprovalModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300">
        
        <!-- Close Button -->
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900">
                Detail Jadwal Bimbingan
            </h3>
            <button onclick="closeJadwalModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Modal Content -->
        <div id="modalContent" class="mb-6 space-y-4">
            <!-- Jadwal Details -->
            <div id="jadwalDetail" class="space-y-4">
                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                    <div id="mahasiswaAvatar" class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                        <span class="font-bold text-blue-700">M</span>
                    </div>
                    <div>
                        <p id="mahasiswaNama" class="font-semibold text-gray-900">Mahasiswa</p>
                        <p id="mahasiswaNim" class="text-xs text-gray-600">-</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal & Waktu</label>
                        <p id="jadwalTanggal" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tempat/Ruangan</label>
                        <p id="jadwalTempat" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Topik Bimbingan</label>
                        <p id="jadwalTopik" class="text-sm text-gray-900">-</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                        <span id="jadwalStatus" class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                            Menunggu
                        </span>
                    </div>
                </div>
            </div>

            <!-- Confirmation Message (Hidden by default) -->
            <div id="confirmationMessage" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <div class="text-3xl mb-2">✓</div>
                <p class="text-sm font-medium text-gray-900 mb-2">Apakah Anda yakin ingin menyetujui jadwal bimbingan ini?</p>
                <p id="confirmationText" class="text-xs text-gray-600"></p>
            </div>

            <!-- Reject Confirmation Message -->
            <div id="rejectConfirmation" class="hidden space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <div class="text-3xl mb-2">✕</div>
                    <p class="text-sm font-medium text-gray-900 mb-2">Apakah Anda yakin ingin menolak jadwal bimbingan ini?</p>
                    <p id="rejectConfirmationText" class="text-xs text-gray-600"></p>
                </div>

                <!-- Alasan Penolakan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Alasan Penolakan (Opsional)</label>
                    <textarea id="rejectionReason" placeholder="Tuliskan alasan penolakan..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none" rows="3"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Mahasiswa akan melihat alasan ini</p>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div id="modalActions" class="flex gap-3">
            <!-- Default Actions -->
            <button onclick="showRejectConfirmation()" class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Tolak
            </button>
            <button onclick="showApproveConfirmation()" class="flex-1 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors duration-200">
                <i class="fas fa-check mr-2"></i>Setujui
            </button>
        </div>

        <!-- Confirmation Actions (Hidden by default) -->
        <div id="confirmationActions" class="hidden flex gap-3">
            <button onclick="cancelConfirmation()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors duration-200">
                Batal
            </button>
            <button id="confirmApproveBtn" onclick="submitApproval()" class="flex-1 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200">
                <i class="fas fa-check mr-2"></i>Ya, Setujui
            </button>
        </div>

        <!-- Rejection Confirmation Actions (Hidden by default) -->
        <div id="rejectConfirmationActions" class="hidden flex gap-3">
            <button onclick="cancelConfirmation()" class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors duration-200">
                Batal
            </button>
            <button id="confirmRejectBtn" onclick="submitRejection()" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Ya, Tolak
            </button>
        </div>
    </div>
</div>

<script>
    let currentJadwalId = null;

    function openJadwalModal(jadwalId) {
        currentJadwalId = jadwalId;

        // Fetch jadwal data
        fetch(`/dospem/jadwal/${jadwalId}`)
            .then(response => response.json())
            .then(data => {
                // Set modal data
                const jadwal = data.data;
                document.getElementById('mahasiswaNama').textContent = jadwal.mahasiswa?.nama || 'Mahasiswa';
                document.getElementById('mahasiswaNim').textContent = jadwal.mahasiswa?.nim || '-';
                document.getElementById('mahasiswaAvatar').innerHTML = `<span class="font-bold text-blue-700">${(jadwal.mahasiswa?.nama || 'M')[0].toUpperCase()}</span>`;
                
                document.getElementById('jadwalTanggal').textContent = `${new Date(jadwal.tanggal).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric'})} Pukul ${jadwal.jam_mulai}`;
                document.getElementById('jadwalTempat').textContent = jadwal.tempat || '-';
                document.getElementById('jadwalTopik').textContent = jadwal.topik || '-';
                
                const statusSpan = document.getElementById('jadwalStatus');
                if (jadwal.status === 'disetujui') {
                    statusSpan.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800';
                    statusSpan.textContent = 'Disetujui';
                } else if (jadwal.status === 'ditolak') {
                    statusSpan.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800';
                    statusSpan.textContent = 'Ditolak';
                } else {
                    statusSpan.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800';
                    statusSpan.textContent = 'Menunggu';
                }

                // Show modal
                document.getElementById('jadwalApprovalModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data jadwal');
            });
    }

    function closeJadwalModal() {
        document.getElementById('jadwalApprovalModal').classList.add('hidden');
        resetModal();
    }

    function showApproveConfirmation() {
        document.getElementById('jadwalDetail').classList.add('hidden');
        document.getElementById('confirmationMessage').classList.remove('hidden');
        document.getElementById('rejectConfirmation').classList.add('hidden');
        
        document.getElementById('modalActions').classList.add('hidden');
        document.getElementById('confirmationActions').classList.remove('hidden');
        document.getElementById('rejectConfirmationActions').classList.add('hidden');
    }

    function showRejectConfirmation() {
        document.getElementById('jadwalDetail').classList.add('hidden');
        document.getElementById('confirmationMessage').classList.add('hidden');
        document.getElementById('rejectConfirmation').classList.remove('hidden');
        
        document.getElementById('modalActions').classList.add('hidden');
        document.getElementById('confirmationActions').classList.add('hidden');
        document.getElementById('rejectConfirmationActions').classList.remove('hidden');
    }

    function cancelConfirmation() {
        document.getElementById('jadwalDetail').classList.remove('hidden');
        document.getElementById('confirmationMessage').classList.add('hidden');
        document.getElementById('rejectConfirmation').classList.add('hidden');
        
        document.getElementById('modalActions').classList.remove('hidden');
        document.getElementById('confirmationActions').classList.add('hidden');
        document.getElementById('rejectConfirmationActions').classList.add('hidden');

        document.getElementById('rejectionReason').value = '';
    }

    function submitApproval() {
        if (!currentJadwalId) return;

        fetch(`/dospem/jadwal/${currentJadwalId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Jadwal bimbingan berhasil disetujui!');
                closeJadwalModal();
                location.reload(); // Reload untuk update list
            } else {
                alert('Error: ' + (data.message || 'Gagal menyetujui jadwal'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyetujui jadwal');
        });
    }

    function submitRejection() {
        if (!currentJadwalId) return;

        const reason = document.getElementById('rejectionReason').value;

        fetch(`/dospem/jadwal/${currentJadwalId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Jadwal bimbingan berhasil ditolak!');
                closeJadwalModal();
                location.reload(); // Reload untuk update list
            } else {
                alert('Error: ' + (data.message || 'Gagal menolak jadwal'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menolak jadwal');
        });
    }

    function resetModal() {
        currentJadwalId = null;
        document.getElementById('jadwalDetail').classList.remove('hidden');
        document.getElementById('confirmationMessage').classList.add('hidden');
        document.getElementById('rejectConfirmation').classList.add('hidden');
        document.getElementById('modalActions').classList.remove('hidden');
        document.getElementById('confirmationActions').classList.add('hidden');
        document.getElementById('rejectConfirmationActions').classList.add('hidden');
        document.getElementById('rejectionReason').value = '';
    }
</script>

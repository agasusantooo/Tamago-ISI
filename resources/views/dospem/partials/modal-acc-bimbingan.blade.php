<!-- Modal untuk ACC/Tolak Bimbingan -->
<div id="modalAccBimbingan" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-xl">
            <h2 class="text-lg font-bold text-white">Konfirmasi Jadwal Bimbingan</h2>
        </div>

        <!-- Content -->
        <div class="px-6 py-4 space-y-4">
            <!-- Detail Jadwal -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="space-y-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">TANGGAL & WAKTU</label>
                        <p class="text-sm font-semibold text-gray-800" id="modal-jadwal-detail">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">TOPIK BIMBINGAN</label>
                        <p class="text-sm font-semibold text-gray-800" id="modal-topik">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">CATATAN MAHASISWA</label>
                        <p class="text-sm text-gray-700" id="modal-catatan">-</p>
                    </div>
                </div>
            </div>

            <!-- Opsi Aksi -->
            <div class="space-y-3">
                <!-- Acc -->
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border-2 border-gray-200 rounded-lg hover:border-green-400 hover:bg-green-50 transition">
                        <input type="radio" name="acc-action" value="approve" class="w-4 h-4 text-green-600">
                        <span class="text-sm font-medium text-gray-700">Terima (ACC) Jadwal Bimbingan ini</span>
                    </label>
                </div>

                <!-- Tolak -->
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border-2 border-gray-200 rounded-lg hover:border-red-400 hover:bg-red-50 transition">
                        <input type="radio" name="acc-action" value="reject" class="w-4 h-4 text-red-600">
                        <span class="text-sm font-medium text-gray-700">Tolak Jadwal Bimbingan ini</span>
                    </label>
                </div>
            </div>

            <!-- Alasan (opsional untuk tolak) -->
            <div id="alasan-container" class="hidden">
                <label class="text-sm font-semibold text-gray-700 block mb-2">Alasan Penolakan (Opsional)</label>
                <textarea 
                    id="modal-alasan" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    rows="3"
                    placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end space-x-3">
            <button 
                onclick="closeModalAccBimbingan()" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Batal
            </button>
            <button 
                onclick="submitAccBimbingan()" 
                id="btn-submit-modal"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                Konfirmasi
            </button>
        </div>
    </div>
</div>

<!-- Hidden Form untuk Submit -->
<form id="form-acc-bimbingan" method="POST" style="display: none;">
    @csrf
    <input type="hidden" id="form-action" name="action" value="">
    <input type="hidden" id="form-bimbingan-id" name="bimbingan_id" value="">
    <input type="hidden" id="form-alasan" name="alasan" value="">
</form>

<script>
let currentBimbinganId = null;
let currentAction = null;

function openModalAccBimbingan(bimbinganId, tanggal, waktu, topik, catatan = '') {
    currentBimbinganId = bimbinganId;
    
    // Populate modal data
    document.getElementById('modal-jadwal-detail').textContent = `${tanggal} Pukul ${waktu}`;
    document.getElementById('modal-topik').textContent = topik || 'Bimbingan umum';
    document.getElementById('modal-catatan').textContent = catatan || '-';
    
    // Reset form
    document.querySelectorAll('input[name="acc-action"]').forEach(r => r.checked = false);
    document.getElementById('modal-alasan').value = '';
    document.getElementById('alasan-container').classList.add('hidden');
    
    // Show modal
    document.getElementById('modalAccBimbingan').classList.remove('hidden');
}

function closeModalAccBimbingan() {
    document.getElementById('modalAccBimbingan').classList.add('hidden');
    currentBimbinganId = null;
    currentAction = null;
}

// Toggle alasan input when reject is selected
document.querySelectorAll('input[name="acc-action"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const alasanContainer = document.getElementById('alasan-container');
        if (this.value === 'reject') {
            alasanContainer.classList.remove('hidden');
        } else {
            alasanContainer.classList.add('hidden');
        }
    });
});

function submitAccBimbingan() {
    const selectedAction = document.querySelector('input[name="acc-action"]:checked');
    
    if (!selectedAction) {
        alert('Pilih aksi terlebih dahulu (Terima atau Tolak)');
        return;
    }

    const action = selectedAction.value;
    const alasan = document.getElementById('modal-alasan').value;

    // Create FormData for submission
    const form = document.getElementById('form-acc-bimbingan');
    form.innerHTML = '';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(csrfInput);
    
    // Add alasan
    const alasanInput = document.createElement('input');
    alasanInput.type = 'hidden';
    alasanInput.name = 'alasan';
    alasanInput.value = alasan;
    form.appendChild(alasanInput);

    // Determine the route based on action
    if (action === 'approve') {
        form.action = `{{ route('dospem.bimbingan.approve', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', currentBimbinganId);
    } else {
        form.action = `{{ route('dospem.bimbingan.reject', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', currentBimbinganId);
    }

    form.method = 'POST';
    form.submit();
}

// Close modal when clicking outside
document.getElementById('modalAccBimbingan').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalAccBimbingan();
    }
});

// Keyboard shortcut - ESC to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('modalAccBimbingan').classList.contains('hidden')) {
        closeModalAccBimbingan();
    }
});
</script>

jau a<!-- Modal Popup untuk ACC/Tolak Bimbingan -->
<div id="accBimbinganModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6 rounded-t-2xl">
            <h2 class="text-2xl font-bold text-white">Konfirmasi Jadwal Bimbingan</h2>
            <p class="text-blue-100 text-sm mt-1">Setujui atau tolak jadwal bimbingan mahasiswa</p>
        </div>

        <!-- Content -->
        <div class="px-8 py-6 space-y-4 max-h-96 overflow-y-auto">
            <!-- Detail Jadwal -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5">
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal & Waktu</label>
                        <p class="text-lg font-bold text-gray-900 mt-1" id="modal-jadwal-detail">-</p>
                    </div>
                    <hr class="border-blue-100">
                    <div>
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Topik Bimbingan</label>
                        <p class="text-sm font-semibold text-gray-800 mt-1" id="modal-topik">-</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Catatan Mahasiswa</label>
                        <p class="text-sm text-gray-700 mt-1 italic" id="modal-catatan">-</p>
                    </div>
                </div>
            </div>

            <!-- Pilihan Aksi -->
            <div class="space-y-3 pt-2">
                <!-- Tombol ACC -->
                <label class="flex items-center space-x-4 cursor-pointer p-4 border-2 border-green-300 rounded-xl hover:bg-green-50 transition-all duration-200 group">
                    <input type="radio" name="bimbingan-action" value="approve" class="w-5 h-5 text-green-600 cursor-pointer">
                    <div class="flex-1">
                        <span class="text-sm font-bold text-green-800 group-hover:text-green-900">Terima (ACC)</span>
                        <p class="text-xs text-green-600">Setujui jadwal bimbingan ini</p>
                    </div>
                    <i class="fas fa-check-circle text-green-600 text-xl group-hover:scale-110 transition-transform"></i>
                </label>

                <!-- Tombol Tolak -->
                <label class="flex items-center space-x-4 cursor-pointer p-4 border-2 border-red-300 rounded-xl hover:bg-red-50 transition-all duration-200 group">
                    <input type="radio" name="bimbingan-action" value="reject" class="w-5 h-5 text-red-600 cursor-pointer">
                    <div class="flex-1">
                        <span class="text-sm font-bold text-red-800 group-hover:text-red-900">Tolak</span>
                        <p class="text-xs text-red-600">Tolak jadwal bimbingan ini</p>
                    </div>
                    <i class="fas fa-times-circle text-red-600 text-xl group-hover:scale-110 transition-transform"></i>
                </label>
            </div>

            <!-- Alasan Penolakan (muncul ketika tolak dipilih) -->
            <div id="alasan-penolakan-container" class="hidden pt-2">
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                    <label class="text-sm font-bold text-red-800 block mb-2">Alasan Penolakan (Opsional)</label>
                    <textarea 
                        id="alasan-penolakan-text" 
                        class="w-full px-4 py-2 border-2 border-red-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                        rows="3"
                        placeholder="Jelaskan mengapa jadwal ini ditolak..."></textarea>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 rounded-b-2xl flex justify-end space-x-3 border-t">
            <button 
                type="button"
                onclick="closeAccBimbinganModal()" 
                class="px-6 py-2 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all duration-200">
                Batal
            </button>
            <button 
                type="button"
                onclick="submitAccBimbingan()" 
                class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center space-x-2">
                <i class="fas fa-check"></i>
                <span>Konfirmasi</span>
            </button>
        </div>
    </div>
</div>

<!-- Hidden Form untuk Submit -->
<form id="form-acc-bimbingan" method="POST" style="display: none;">
    @csrf
    <input type="hidden" id="form-alasan" name="alasan" value="">
</form>

<script>
let currentBimbinganId = null;

// Toggle alasan ketika radio berubah
document.querySelectorAll('input[name="bimbingan-action"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const alasanContainer = document.getElementById('alasan-penolakan-container');
        if (this.value === 'reject') {
            alasanContainer.classList.remove('hidden');
        } else {
            alasanContainer.classList.add('hidden');
        }
    });
});

function openAccBimbinganModal(bimbinganId, tanggal, waktu, topik, catatan = '') {
    currentBimbinganId = bimbinganId;
    
    // Populate detail
    document.getElementById('modal-jadwal-detail').textContent = `${tanggal} Pukul ${waktu}`;
    document.getElementById('modal-topik').textContent = topik || 'Bimbingan umum';
    document.getElementById('modal-catatan').textContent = catatan || 'Tidak ada catatan';
    
    // Reset form
    document.querySelectorAll('input[name="bimbingan-action"]').forEach(r => r.checked = false);
    document.getElementById('alasan-penolakan-text').value = '';
    document.getElementById('alasan-penolakan-container').classList.add('hidden');
    
    // Show modal
    const modal = document.getElementById('accBimbinganModal');
    modal.classList.remove('hidden');
    
    // Add animation
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
        modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeAccBimbinganModal() {
    const modal = document.getElementById('accBimbinganModal');
    const content = modal.querySelector('.bg-white');
    
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        currentBimbinganId = null;
    }, 300);
}

function submitAccBimbingan() {
    const submitBtn = document.querySelector('#accBimbinganModal button[type="button"]');
    if (submitBtn.disabled) return;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

    const selectedAction = document.querySelector('input[name="bimbingan-action"]:checked');

    if (!selectedAction) {
        alert('⚠️ Pilih aksi terlebih dahulu (Terima atau Tolak)!');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> <span>Konfirmasi</span>';
        return;
    }

    if (!currentBimbinganId) {
        alert('❌ Error: Bimbingan ID tidak ditemukan!');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> <span>Konfirmasi</span>';
        return;
    }

    const action = selectedAction.value;
    const alasan = document.getElementById('alasan-penolakan-text').value;
    const form = document.getElementById('form-acc-bimbingan');

    // Set action ke URL untuk AJAX submit
    const approveUrl = `{{ route('dospem.bimbingan.approve', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', currentBimbinganId);
    const rejectUrl = `{{ route('dospem.bimbingan.reject', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', currentBimbinganId);
    const url = action === 'approve' ? approveUrl : rejectUrl;

    // Submit via fetch (AJAX)
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: new URLSearchParams({ alasan: alasan })
    })
    .then(res => res.json())
    .then(data => {
        if (data && (data.status === 'success' || data.success)) {
            // Update local event list and UI
            if (window.allJadwalEvents) {
                const j = window.allJadwalEvents.find(x => String(x.id) === String(currentBimbinganId));
                if (j) j.status = action === 'approve' ? 'approved' : 'rejected';
            }

            // Refresh list and calendar
            if (typeof renderJadwalList === 'function') renderJadwalList();
            if (window.jadwalCalendar && typeof window.jadwalCalendar.refetchEvents === 'function') window.jadwalCalendar.refetchEvents();

            closeAccBimbinganModal();
            alert(data.message || 'Aksi berhasil.');
        } else {
            alert(data.message || 'Terjadi kesalahan saat memproses aksi.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mengirim permintaan: ' + (err.message || err));
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> <span>Konfirmasi</span>';
    });
}

// Close modal when clicking outside
document.getElementById('accBimbinganModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAccBimbinganModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('accBimbinganModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeAccBimbinganModal();
        }
    }
});

// Add smooth animations on load
const style = document.createElement('style');
style.textContent = `
    #accBimbinganModal .bg-white {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: center;
    }
    
    #accBimbinganModal {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
</script>

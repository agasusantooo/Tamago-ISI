<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Mahasiswa - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">

                    <div class="mb-4">
                        <a href="{{ route('dospem.mahasiswa-bimbingan') }}" class="text-sm text-blue-600 hover:underline">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Mahasiswa
                        </a>
                    </div>

                    <div class="grid grid-cols-12 gap-6">
                        <!-- Left column: profile + tabs -->
                        <div class="col-span-8">
                            <!-- Profil Mahasiswa -->
                            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($mahasiswa->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-blue-900">{{ $mahasiswa->name }}</h2>
                                        <p class="text-sm text-gray-500">NIM: {{ $mahasiswa->nim }}</p>
                                        <p class="text-sm text-gray-500">Email: {{ $mahasiswa->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h3 class="font-semibold text-gray-800">Judul Tugas Akhir</h3>
                                    <div class="mt-2 p-4 bg-gray-100 rounded-lg">{{ $mahasiswa->judul_ta ?? 'Belum ada judul' }}</div>
                                </div>
                            </div>

                            <!-- Tabs -->
                            <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                                <nav class="flex space-x-2 border-b">
                                    <button onclick="switchTab('proposal')" class="tab-btn px-4 py-3 rounded-t-md text-sm font-medium border-b-2 border-blue-600 text-blue-600 bg-white" data-tab="proposal">Pengajuan Proposal</button>
                                    <button onclick="switchTab('bimbingan')" class="tab-btn px-4 py-3 rounded-t-md text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-800" data-tab="bimbingan">Bimbingan</button>
                                    <button onclick="switchTab('monitoring')" class="tab-btn px-4 py-3 rounded-t-md text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-800" data-tab="monitoring">Monitoring Progress</button>
                                    <button onclick="switchTab('produksi')" class="tab-btn px-4 py-3 rounded-t-md text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-800" data-tab="produksi">Persetujuan Produksi</button>
                                </nav>
                            </div>

                            <!-- Tab: Proposal -->
                            <div id="proposal-tab" class="tab-content bg-white rounded-xl shadow-sm p-6 mb-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Pengajuan Proposal</h3>
                                @if(!empty($proposals) && count($proposals) > 0)
                                    <div class="space-y-4">
                                        @foreach($proposals as $proposal)
                                            <div class="border rounded-lg p-4 bg-gray-50 max-w-xl mx-auto">
                                                <div class="flex justify-between items-start mb-3">
                                                    <div class="max-w-xs">
                                                        <h4 class="font-semibold text-gray-800 truncate">{{ $proposal['judul'] ?? 'Proposal' }}</h4>
                                                        <p class="text-sm text-gray-500">Diajukan: {{ $proposal['tanggal_pengajuan'] ?? '-' }}</p>
                                                    </div>
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium @if($proposal['status'] === 'pending') bg-yellow-100 text-yellow-800 @elseif($proposal['status'] === 'approved') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($proposal['status'] ?? 'pending') }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-700 mb-3 truncate max-w-lg" title="{{ $proposal['deskripsi'] ?? '' }}">{{ $proposal['deskripsi'] ?? '' }}</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @if($proposal['file_proposal'])
                                                        <a href="{{ asset($proposal['file_proposal']) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                                            <i class="fas fa-file-pdf mr-1"></i>Lihat Proposal
                                                        </a>
                                                    @endif
                                                    @if(in_array($proposal['status'], ['diajukan', 'review', 'revisi']))
                                                        <button type="button" class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700" onclick="openProposalModal({{ $proposal['id'] }})">
                                                            <i class="fas fa-edit mr-1"></i>Tindakan
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Modal Aksi Proposal dengan Pilihan Status -->
                                    <div id="proposalModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden p-4">
                                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
                                            <!-- Header -->
                                            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6 rounded-t-2xl">
                                                <h2 class="text-2xl font-bold text-white">Tindakan Proposal</h2>
                                                <p class="text-blue-100 text-sm mt-1">Pilih status dan berikan feedback untuk proposal</p>
                                            </div>

                                            <!-- Form -->
                                            <form id="proposalModalForm" method="POST" action="#" class="px-8 py-6">
                                                @csrf
                                                <input type="hidden" name="_method" id="proposalModalMethod" value="PATCH">
                                                
                                                <!-- Pilihan Status -->
                                                <div class="mb-6">
                                                    <label class="text-sm font-bold text-gray-700 mb-3 block uppercase tracking-wider">Pilih Status Proposal</label>
                                                    <div class="space-y-2">
                                                        <label class="flex items-center p-3 border-2 border-blue-300 rounded-lg hover:bg-blue-50 cursor-pointer">
                                                            <input type="radio" name="status" value="review" class="w-4 h-4" required>
                                                            <span class="ml-3 font-medium text-gray-800">Review</span>
                                                        </label>
                                                        <label class="flex items-center p-3 border-2 border-yellow-300 rounded-lg hover:bg-yellow-50 cursor-pointer">
                                                            <input type="radio" name="status" value="revisi" class="w-4 h-4">
                                                            <span class="ml-3 font-medium text-gray-800">Revisi</span>
                                                        </label>
                                                        <label class="flex items-center p-3 border-2 border-green-300 rounded-lg hover:bg-green-50 cursor-pointer">
                                                            <input type="radio" name="status" value="disetujui" class="w-4 h-4">
                                                            <span class="ml-3 font-medium text-green-800">Disetujui (ACC)</span>
                                                        </label>
                                                        <label class="flex items-center p-3 border-2 border-red-300 rounded-lg hover:bg-red-50 cursor-pointer">
                                                            <input type="radio" name="status" value="ditolak" class="w-4 h-4">
                                                            <span class="ml-3 font-medium text-red-800">Ditolak</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Feedback/Catatan -->
                                                <div class="mb-6">
                                                    <label class="text-sm font-bold text-gray-700 mb-2 block">Feedback & Catatan Dosen</label>
                                                    <textarea 
                                                        name="feedback" 
                                                        rows="4" 
                                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="Tulis feedback, saran, atau alasan penolakan untuk mahasiswa..."
                                                        required></textarea>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="flex justify-end gap-3 pt-4 border-t">
                                                    <button 
                                                        type="button"
                                                        onclick="closeProposalModal()" 
                                                        class="px-6 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                                                        Batal
                                                    </button>
                                            <button
                                                type="button"
                                                onclick="submitProposalForm()"
                                                class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                                                <i class="fas fa-check"></i>
                                                Kirim Tindakan
                                            </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <script>
                                        let currentProposalId = null;

                                        function openProposalModal(id) {
                                            currentProposalId = id;
                                            var modal = document.getElementById('proposalModal');
                                            var form = document.getElementById('proposalModalForm');
                                            form.action = '/dospem/proposal/' + id + '/update-status';
                                            form.reset();
                                            modal.classList.remove('hidden');
                                        }

                                        function closeProposalModal() {
                                            document.getElementById('proposalModal').classList.add('hidden');
                                            currentProposalId = null;
                                        }

                                        function submitProposalForm() {
                                            const form = document.getElementById('proposalModalForm');
                                            const submitBtn = document.querySelector('#proposalModalForm button[type="button"]');
                                            submitBtn.disabled = true;
                                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

                                            const formData = new FormData(form);
                                            const status = formData.get('status');
                                            const feedback = formData.get('feedback');

                                            if (!status) {
                                                alert('Pilih status terlebih dahulu!');
                                                submitBtn.disabled = false;
                                                submitBtn.innerHTML = '<i class="fas fa-check"></i> Kirim Tindakan';
                                                return;
                                            }

                                            if (!feedback.trim()) {
                                                alert('Feedback tidak boleh kosong!');
                                                submitBtn.disabled = false;
                                                submitBtn.innerHTML = '<i class="fas fa-check"></i> Kirim Tindakan';
                                                return;
                                            }

                                            console.log('Submitting proposal with:', {status, feedback, action: form.action});

                                            fetch(form.action, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    status: status,
                                                    feedback: feedback
                                                })
                                            })
                                            .then(res => {
                                                console.log('Response status:', res.status);
                                                return res.json();
                                            })
                                            .then(data => {
                                                console.log('Response data:', data);
                                                if (data.success) {
                                                    alert('✅ Tindakan proposal berhasil disimpan!');
                                                    closeProposalModal();
                                                    location.reload();
                                                } else {
                                                    alert('❌ Error: ' + (data.message || 'Gagal menyimpan tindakan'));
                                                    submitBtn.disabled = false;
                                                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Kirim Tindakan';
                                                }
                                            })
                                            .catch(err => {
                                                console.error('Error:', err);
                                                alert('❌ Error: ' + err.message);
                                                submitBtn.disabled = false;
                                                submitBtn.innerHTML = '<i class="fas fa-check"></i> Kirim Tindakan';
                                            });
                                        }
                                    </script>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                        <p class="text-gray-500">Tidak ada pengajuan proposal.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab: Bimbingan -->
                            <div id="bimbingan-tab" class="tab-content hidden bg-white rounded-xl shadow-sm p-6 mb-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Permintaan Jadwal Bimbingan</h3>
                                @if(!empty($jadwal_bimbingan) && count($jadwal_bimbingan) > 0)
                                    <div class="space-y-4">
                                        @foreach($jadwal_bimbingan as $jadwal)
                                            <div class="border rounded-lg p-4 bg-gray-50 max-w-2xl mx-auto">
                                                <div class="flex justify-between items-start mb-3">
                                                    <div class="max-w-xs">
                                                        <h4 class="font-semibold text-gray-800">{{ $jadwal['tanggal'] ?? '-' }} - {{ $jadwal['waktu'] ?? '-' }}</h4>
                                                        <p class="text-sm text-gray-500">Diajukan: {{ $jadwal['created_at'] ?? '-' }}</p>
                                                    </div>
                                                    <span class="px-3 py-1 rounded-full text-xs font-medium @if($jadwal['status'] === 'pending') bg-yellow-100 text-yellow-800 @elseif($jadwal['status'] === 'approved') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($jadwal['status'] ?? 'pending') }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-700 mb-3">{{ $jadwal['deskripsi'] ?? '' }}</p>
                                                <div class="flex flex-wrap gap-2">
                                                    <p class="text-sm text-gray-600 mt-1">📌 {{ $jadwal['topik'] ?? 'Bimbingan umum' }}</p>
                                                </div>
                                                @if($jadwal['status'] === 'pending')
                                                    <button
                                                        type="button"
                                                        onclick="openAccBimbinganModal(
                                                            {{ $jadwal['id'] ?? 0 }},
                                                            '{{ $jadwal['tanggal'] ?? '-' }}',
                                                            '{{ $jadwal['waktu'] ?? '-' }}',
                                                            '{{ $jadwal['topik'] ?? 'Bimbingan umum' }}',
                                                            '{{ $jadwal['deskripsi'] ?? '' }}'
                                                        )"
                                                        class="ml-4 px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 hover:shadow-lg transition-all whitespace-nowrap cursor-pointer">
                                                        <i class="fas fa-check-circle mr-2"></i>ACC/Tolak
                                                    </button>
                                                @else
                                                    <span class="ml-4 px-4 py-2 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>{{ ucfirst($jadwal['status'] ?? 'pending') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                        <p class="text-gray-500">Tidak ada permintaan jadwal bimbingan.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab: Monitoring -->
                            <div id="monitoring-tab" class="tab-content hidden">

                            <!-- Riwayat Bimbingan table -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-blue-800">Riwayat Bimbingan</h3>
                                    <span class="text-sm text-gray-500">{{ count($mahasiswa->riwayat_bimbingan ?? []) }} Pertemuan</span>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-xs text-gray-500">
                                                <th class="py-3 pr-6">Tanggal</th>
                                                <th class="py-3 pr-6">Topik Pembahasan</th>
                                                <th class="py-3 pr-6">Catatan Mahasiswa</th>
                                                <th class="py-3 pr-6">File Pendukung</th>
                                                <th class="py-3 pr-6">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse($mahasiswa->riwayat_bimbingan as $riwayat)
                                            <tr class="align-top">
                                                <td class="py-4 pr-6 w-40 text-gray-700">
                                                    <div class="font-semibold">{{ $riwayat['tanggal'] ?? '-' }}</div>
                                                    <div class="text-xs text-gray-400">{{ $riwayat['waktu'] ?? '' }}</div>
                                                </td>
                                                <td class="py-4 pr-6 text-gray-800">{{ $riwayat['topik'] ?? '-' }}</td>
                                                <td class="py-4 pr-6 text-gray-600">{{ $riwayat['catatan_mahasiswa'] ?? $riwayat['catatan'] ?? '-' }}</td>
                                                <td class="py-4 pr-6 text-blue-600">
                                                    @if(!empty($riwayat['file']))
                                                        <a href="{{ asset($riwayat['file']) }}" class="underline">Lihat</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="py-4 pr-6">
                                                    @if(($riwayat['status'] ?? '') === 'Selesai')
                                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">Selesai</span>
                                                    @else
                                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">{{ $riwayat['status'] ?? 'Menunggu' }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="py-6 text-center text-gray-500">Belum ada riwayat bimbingan.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                            <!-- Tab: Produksi -->
                            <div id="produksi-tab" class="tab-content hidden bg-white rounded-xl shadow-sm p-6 mb-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Persetujuan Produksi</h3>
                                @php
                                    $produksiList = isset($produksi) && is_array($produksi) ? $produksi : [];
                                @endphp
                                
                                @if(!empty($produksiList) && count($produksiList) > 0)
                                    <div class="space-y-6">
                                        @foreach($produksiList as $prod)
                                            <!-- Pra Produksi Section -->
                                            @if($prod['file_skenario'] || $prod['file_storyboard'] || $prod['file_dokumen_pendukung'])
                                                <div class="border rounded-lg p-4 bg-gray-50">
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div>
                                                            <h4 class="font-semibold text-gray-800 flex items-center">
                                                                <i class="fas fa-film text-blue-600 mr-2"></i>Pra Produksi
                                                            </h4>
                                                            <p class="text-xs text-gray-500 mt-1">Tanggal Upload: {{ $prod['tanggal_upload_pra'] ?? '-' }}</p>
                                                        </div>
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                                            @if($prod['status_pra_produksi'] === 'disetujui') bg-green-100 text-green-800
                                                            @elseif($prod['status_pra_produksi'] === 'revisi') bg-orange-100 text-orange-800
                                                            @elseif($prod['status_pra_produksi'] === 'ditolak') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $prod['status_pra_produksi'] ?? 'menunggu_review')) }}
                                                        </span>
                                                    </div>

                                                    <!-- Files List -->
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                                                        @if($prod['file_skenario'])
                                                            <div class="flex items-center p-2 bg-white rounded border border-gray-200">
                                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                                                <span class="text-xs text-gray-700">File Skenario</span>
                                                            </div>
                                                        @endif
                                                        @if($prod['file_storyboard'])
                                                            <div class="flex items-center p-2 bg-white rounded border border-gray-200">
                                                                <i class="fas fa-images text-green-500 mr-2"></i>
                                                                <span class="text-xs text-gray-700">File Storyboard</span>
                                                            </div>
                                                        @endif
                                                        @if($prod['file_dokumen_pendukung'])
                                                            <div class="flex items-center p-2 bg-white rounded border border-gray-200">
                                                                <i class="fas fa-file text-blue-500 mr-2"></i>
                                                                <span class="text-xs text-gray-700">Dokumen Pendukung</span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Feedback -->
                                                    @if($prod['feedback_pra_produksi'])
                                                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                                                            <p class="text-xs font-semibold text-yellow-900 mb-1">💬 Feedback Dosen:</p>
                                                            <p class="text-sm text-gray-700">{{ $prod['feedback_pra_produksi'] }}</p>
                                                        </div>
                                                    @endif

                                                    <!-- Action Button -->
                                                    @if($prod['status_pra_produksi'] === 'menunggu_review')
                                                        <button type="button" onclick="openProduksiModal({{ $prod['id'] }}, 'pra')"
                                                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                            <i class="fas fa-check mr-1"></i>Review & Feedback
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Produksi Akhir Section -->
                                            @if($prod['file_produksi_akhir'])
                                                <div class="border rounded-lg p-4 bg-gray-50 mt-4">
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div>
                                                            <h4 class="font-semibold text-gray-800 flex items-center">
                                                                <i class="fas fa-video text-purple-600 mr-2"></i>Produksi Akhir
                                                            </h4>
                                                            <p class="text-xs text-gray-500 mt-1">Tanggal Upload: {{ $prod['tanggal_upload_akhir'] ?? '-' }}</p>
                                                        </div>
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                                            @if($prod['status_produksi_akhir'] === 'disetujui') bg-green-100 text-green-800
                                                            @elseif($prod['status_produksi_akhir'] === 'revisi') bg-orange-100 text-orange-800
                                                            @elseif($prod['status_produksi_akhir'] === 'ditolak') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $prod['status_produksi_akhir'] ?? 'menunggu_review')) }}
                                                        </span>
                                                    </div>

                                                    <!-- File -->
                                                    <div class="flex items-center p-3 bg-white rounded border border-gray-200 mb-4">
                                                        <i class="fas fa-file-video text-purple-500 mr-2"></i>
                                                        <span class="text-sm text-gray-700">File Karya Final</span>
                                                    </div>

                                                    <!-- Feedback -->
                                                    @if($prod['feedback_produksi_akhir'])
                                                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                                                            <p class="text-xs font-semibold text-yellow-900 mb-1">💬 Feedback Dosen:</p>
                                                            <p class="text-sm text-gray-700">{{ $prod['feedback_produksi_akhir'] }}</p>
                                                        </div>
                                                    @endif

                                                    <!-- Action Button -->
                                                    @if($prod['status_produksi_akhir'] === 'menunggu_review')
                                                        <button type="button" onclick="openProduksiModal({{ $prod['id'] }}, 'akhir')"
                                                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                            <i class="fas fa-check mr-1"></i>Review & Feedback
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3 block"></i>
                                        <p class="text-gray-500">Belum ada file produksi yang diajukan.</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
<script>
    let currentProduksiId = null;
    let currentProduksiType = null; // 'pra' atau 'akhir'

    function openProduksiModal(produksiId, type) {
        currentProduksiId = produksiId;
        currentProduksiType = type;
        const modal = document.getElementById('produksiModal');
        const title = document.getElementById('produksiModalTitle');
        const form = document.getElementById('produksiModalForm');
        
        title.textContent = type === 'pra' 
            ? 'Review Pra Produksi' 
            : 'Review Produksi Akhir';
        
        form.reset();
        modal.classList.remove('hidden');
    }

    function closeProduksiModal() {
        const modal = document.getElementById('produksiModal');
        modal.classList.add('hidden');
        currentProduksiId = null;
        currentProduksiType = null;
    }

    // Handle produksi form submission
    document.getElementById('produksiModalForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const status = document.querySelector('input[name="produksi_status"]:checked')?.value;
        const feedback = document.querySelector('textarea[name="produksi_feedback"]').value;

        if (!status) {
            alert('⚠️ Pilih status terlebih dahulu!');
            return;
        }

        if (!feedback.trim() || feedback.length < 5) {
            alert('⚠️ Feedback minimal 5 karakter!');
            return;
        }

        if (!currentProduksiId || !currentProduksiType) {
            alert('❌ Error: Data produksi tidak lengkap.');
            return;
        }

        // Tentukan URL berdasarkan type
        const baseUrl = currentProduksiType === 'pra'
            ? '{{ route("dospem.produksi.pra-produksi", ["id" => "PLACEHOLDER"]) }}'
            : '{{ route("dospem.produksi.produksi-akhir", ["id" => "PLACEHOLDER"]) }}';
        
        const url = baseUrl.replace('PLACEHOLDER', currentProduksiId);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                feedback: feedback
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data && (data.status === 'success' || data.success)) {
                alert('✅ ' + (data.message || 'Feedback berhasil dikirim!'));
                closeProduksiModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('❌ ' + (data.message || 'Terjadi kesalahan.'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Kirim Feedback';
            }
        })
        .catch(err => {
            console.error(err);
            alert('❌ Gagal mengirim: ' + (err.message || err));
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Kirim Feedback';
        });
    });

    // Close modal when clicking outside
    document.getElementById('produksiModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeProduksiModal();
        }
    });

    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-blue-600', 'text-blue-600', 'border-b-2');
            el.classList.add('border-transparent', 'text-gray-600');
        });

        // Show selected tab
        const tabEl = document.getElementById(tabName + '-tab');
        if (tabEl) {
            tabEl.classList.remove('hidden');
        }

        // Highlight tab button
        const btnEl = document.querySelector(`[data-tab="${tabName}"]`);
        if (btnEl) {
            btnEl.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
            btnEl.classList.remove('border-transparent', 'text-gray-600');
        }
    }

    // Handle show/hide rejection reason based on radio selection
    document.querySelectorAll('input[name="bimbingan_action"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const rejectContainer = document.getElementById('reject-reason-container');
            if (this.value === 'reject') {
                rejectContainer.classList.remove('hidden');
            } else {
                rejectContainer.classList.add('hidden');
            }
        });
    });

    // Handle form submission
    document.getElementById('feedbackForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const action = document.querySelector('input[name="bimbingan_action"]:checked')?.value;
        const catatan = document.querySelector('textarea[name="catatan_ringkas"]').value;
        const rejectionReason = document.querySelector('textarea[name="rejection_reason"]')?.value || '';

        if (!action) {
            alert('⚠️ Pilih tindakan terlebih dahulu (Terima atau Tolak)!');
            return;
        }

        // Get the mahasiswa ID from the route
        const mahasiswaId = '{{ $mahasiswa->id ?? $mahasiswa->nim ?? 0 }}';
        
        // Determine the URL based on action
        const url = action === 'approve' 
            ? `{{ route('dospem.bimbingan.approve', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', '{{ $mahasiswa->id ?? $mahasiswa->nim ?? 0 }}')
            : `{{ route('dospem.bimbingan.reject', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', '{{ $mahasiswa->id ?? $mahasiswa->nim ?? 0 }}');

        // Send request
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams({
                catatan_ringkas: catatan,
                alasan: rejectionReason
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data && (data.status === 'success' || data.success)) {
                alert('✅ ' + (data.message || 'Aksi berhasil.'));
                // Reset form
                document.getElementById('feedbackForm').reset();
                document.getElementById('reject-reason-container').classList.add('hidden');
                // Optional: reload page to show updated status
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('❌ ' + (data.message || 'Terjadi kesalahan.'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('❌ Gagal mengirim permintaan: ' + (err.message || err));
        });
    });
</script>

<!-- Produksi Review Modal -->
<div id="produksiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-96 overflow-y-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6 rounded-t-2xl">
            <h2 id="produksiModalTitle" class="text-2xl font-bold text-white">Review Produksi</h2>
            <p class="text-blue-100 text-sm mt-1">Berikan feedback dan pilih status</p>
        </div>

        <!-- Form -->
        <form id="produksiModalForm" method="POST" action="#" class="px-8 py-6">
            @csrf
            
            <!-- Status Options -->
            <div class="mb-6">
                <label class="text-sm font-bold text-gray-700 mb-3 block uppercase tracking-wider">Pilih Status</label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 border-2 border-green-300 rounded-lg hover:bg-green-50 cursor-pointer">
                        <input type="radio" name="produksi_status" value="disetujui" class="w-4 h-4" required>
                        <span class="ml-3 font-medium text-green-800">Disetujui</span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-orange-300 rounded-lg hover:bg-orange-50 cursor-pointer">
                        <input type="radio" name="produksi_status" value="revisi" class="w-4 h-4">
                        <span class="ml-3 font-medium text-orange-800">Perlu Revisi</span>
                    </label>
                    <label class="flex items-center p-3 border-2 border-red-300 rounded-lg hover:bg-red-50 cursor-pointer">
                        <input type="radio" name="produksi_status" value="ditolak" class="w-4 h-4">
                        <span class="ml-3 font-medium text-red-800">Ditolak</span>
                    </label>
                </div>
            </div>

            <!-- Feedback -->
            <div class="mb-6">
                <label class="text-sm font-bold text-gray-700 mb-2 block">Feedback & Catatan</label>
                <textarea 
                    name="produksi_feedback" 
                    rows="4" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    placeholder="Tulis feedback, saran, atau alasan penolakan..."
                    required minlength="5"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button 
                    type="button"
                    onclick="closeProduksiModal()" 
                    class="px-6 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                    Batal
                </button>
                <button 
                    type="submit"
                    class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i class="fas fa-check"></i>
                    Kirim Feedback
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Include Modal Component -->
@include('dospem.modals.acc-bimbingan-modal')

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Bimbingan - Tamago ISI</title>
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
</head>
<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Header Stats -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-blue-800 mb-1">Jadwal Bimbingan</h1>
                        <div class="text-sm text-gray-600">Mahasiswa Aktif: <span id="dospemMahasiswaAktif" class="font-semibold text-blue-700">{{ $mahasiswaAktifCount ?? 0 }}</span> | Tugas Review: <span id="dospemTugasReview" class="font-semibold text-blue-700">{{ $tugasReview ?? 0 }}</span></div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="mb-6 flex space-x-2 border-b border-gray-200">
                        <button onclick="switchJadwalTab('calendar')" class="jadwal-tab-btn px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-medium" data-tab="calendar">
                            <i class="fas fa-calendar mr-2"></i>Kalender
                        </button>
                        <button onclick="switchJadwalTab('list')" class="jadwal-tab-btn px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-800 font-medium" data-tab="list">
                            <i class="fas fa-list mr-2"></i>Daftar Permintaan
                        </button>
                    </div>

                    <!-- Tab: Calendar View -->
                    <div id="calendar-tab" class="jadwal-tab-content bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Jadwal Bimbingan - Kalender</h3>
                        <div id="calendar"></div>
                    </div>

                    <!-- Tab: List View -->
                    <div id="list-tab" class="jadwal-tab-content hidden bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Daftar Permintaan Jadwal Bimbingan</h3>
                        
                        <!-- Filter buttons -->
                        <div class="mb-4 flex space-x-2">
                            <button onclick="filterJadwalStatus('all')" class="filter-btn px-4 py-2 rounded-md text-sm bg-blue-100 text-blue-700 font-medium border border-blue-300" data-status="all">Semua</button>
                            <button onclick="filterJadwalStatus('pending')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:border-yellow-300" data-status="pending">Menunggu</button>
                            <button onclick="filterJadwalStatus('approved')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:border-green-300" data-status="approved">Disetujui</button>
                            <button onclick="filterJadwalStatus('rejected')" class="filter-btn px-4 py-2 rounded-md text-sm bg-white text-gray-700 border border-gray-300 hover:border-red-300" data-status="rejected">Ditolak</button>
                        </div>

                        <!-- List of jadwal requests -->
                        <div id="jadwal-list" class="space-y-3">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
        <!-- Include ACC/Tolak modal (blade + JS) -->
        @include('dospem.modals.acc-bimbingan-modal')


    <!-- Modal for viewing event details -->
    <div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800"></h3>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div id="modalContent" class="space-y-3 mb-6">
                <!-- Content populated by JavaScript -->
            </div>

            <div id="modalActions" class="flex space-x-2">
                <!-- Action buttons populated by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        window.allJadwalEvents = [];
        let currentFilter = 'all';

        function switchJadwalTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.jadwal-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.jadwal-tab-btn').forEach(el => {
                el.classList.remove('border-blue-600', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab
            const tabEl = document.getElementById(tabName + '-tab');
            if (tabEl) {
                tabEl.classList.remove('hidden');
            }

            // Highlight button
            const btnEl = document.querySelector(`[data-tab="${tabName}"]`);
            if (btnEl) {
                btnEl.classList.add('border-blue-600', 'text-blue-600');
                btnEl.classList.remove('border-transparent', 'text-gray-600');
            }

            // Load list when switching to list tab
            if (tabName === 'list') {
                renderJadwalList();
            }
        }

        function filterJadwalStatus(status) {
            currentFilter = status;
            
            // Update button styles
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.status === status) {
                    btn.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-300');
                    btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                } else {
                    btn.classList.remove('bg-blue-100', 'text-blue-700', 'border-blue-300');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                }
            });

            renderJadwalList();
        }

        function renderJadwalList() {
            const jadwalList = document.getElementById('jadwal-list');

            let filtered = window.allJadwalEvents || [];
            if (currentFilter !== 'all') {
                filtered = (window.allJadwalEvents || []).filter(j => j.status === currentFilter);
            }

            if (filtered.length === 0) {
                jadwalList.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada jadwal bimbingan.</div>';
                return;
            }

            // Build HTML using data-attributes for safe passing of strings
            jadwalList.innerHTML = filtered.map(jadwal => {
                const badgeClass = jadwal.status === 'approved' ? 'bg-green-100 text-green-800' : (jadwal.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                const badgeText = jadwal.status === 'approved' ? 'Disetujui' : (jadwal.status === 'rejected' ? 'Ditolak' : 'Menunggu');

                const d_tanggal = encodeURIComponent(jadwal.tanggal || '');
                const d_waktu = encodeURIComponent(jadwal.waktu_mulai || '');
                const d_topik = encodeURIComponent(jadwal.topik || '');
                const d_catatan = encodeURIComponent(jadwal.catatan || '');

                return `
                <div class="border rounded-lg p-4 bg-white hover:bg-gray-50 transition flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h4 class="font-semibold text-gray-800">${jadwal.mahasiswa_name || 'Mahasiswa'}</h4>
                            <span class="text-xs px-2 py-1 rounded-full ${badgeClass}">${badgeText}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">
                            <i class="fas fa-calendar-alt mr-2"></i>${formatDate(jadwal.tanggal)} Pukul ${jadwal.waktu_mulai || '10:00'}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-book mr-2"></i>${jadwal.topik || 'Bimbingan'}
                        </p>
                    </div>
                    <div class="ml-4 flex space-x-2">
                        ${jadwal.status === 'pending' ? `
                            <button class="btn-acc px-3 py-2 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition" data-id="${jadwal.id}" data-tanggal="${d_tanggal}" data-waktu="${d_waktu}" data-topik="${d_topik}" data-catatan="${d_catatan}">
                                <i class="fas fa-check mr-1"></i>ACC
                            </button>
                            <button class="btn-reject px-3 py-2 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition" data-id="${jadwal.id}" data-tanggal="${d_tanggal}" data-waktu="${d_waktu}" data-topik="${d_topik}" data-catatan="${d_catatan}">
                                <i class="fas fa-times mr-1"></i>Tolak
                            </button>
                        ` : `
                            <button onclick="viewEventDetails(${jadwal.id})" class="px-3 py-2 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </button>
                        `}
                    </div>
                </div>
            `;
            }).join('');

            // attach click handlers for ACC/Tolak buttons
            document.querySelectorAll('.btn-acc').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const tanggal = decodeURIComponent(this.dataset.tanggal || '');
                    const waktu = decodeURIComponent(this.dataset.waktu || '');
                    const topik = decodeURIComponent(this.dataset.topik || '');
                    const catatan = decodeURIComponent(this.dataset.catatan || '');
                    openAccBimbinganModal(id, tanggal, waktu, topik, catatan);
                });
            });
            document.querySelectorAll('.btn-reject').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const tanggal = decodeURIComponent(this.dataset.tanggal || '');
                    const waktu = decodeURIComponent(this.dataset.waktu || '');
                    const topik = decodeURIComponent(this.dataset.topik || '');
                    const catatan = decodeURIComponent(this.dataset.catatan || '');
                    openAccBimbinganModal(id, tanggal, waktu, topik, catatan);
                });
            });
        }

        function viewEventDetails(id) {
            const jadwal = window.allJadwalEvents.find(j => j.id === id);
            if (!jadwal) return;

            const modal = document.getElementById('eventModal');
            document.getElementById('modalTitle').textContent = jadwal.mahasiswa_name || 'Detail Jadwal';
            
            document.getElementById('modalContent').innerHTML = `
                <div>
                    <p class="text-sm text-gray-600"><strong>Tanggal:</strong> ${formatDate(jadwal.tanggal)}</p>
                    <p class="text-sm text-gray-600"><strong>Waktu:</strong> ${jadwal.waktu_mulai || '10:00'} - ${jadwal.waktu_selesai || '11:00'}</p>
                    <p class="text-sm text-gray-600"><strong>Topik:</strong> ${jadwal.topik || 'Bimbingan'}</p>
                    <p class="text-sm text-gray-600"><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-xs font-medium ${
                            jadwal.status === 'approved' ? 'bg-green-100 text-green-800' :
                            jadwal.status === 'rejected' ? 'bg-red-100 text-red-800' :
                            'bg-yellow-100 text-yellow-800'
                        }">
                            ${jadwal.status === 'approved' ? 'Disetujui' : jadwal.status === 'rejected' ? 'Ditolak' : 'Menunggu'}
                        </span>
                    </p>
                </div>
            `;

            document.getElementById('modalActions').innerHTML = `
                <button onclick="closeEventModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Tutup</button>
            `;

            modal.classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        function approveJadwal(id) {
            if (confirm('Setujui jadwal bimbingan ini?')) {
                fetch('{{ route("dospem.bimbingan.approve", ":id") }}'.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.status === 'success') {
                        const jadwal = window.allJadwalEvents.find(j => j.id === id);
                        if (jadwal) jadwal.status = 'approved';
                        renderJadwalList();
                        if (window.jadwalCalendar) window.jadwalCalendar.refetchEvents();
                        alert('Jadwal bimbingan disetujui!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function rejectJadwal(id) {
            if (confirm('Tolak jadwal bimbingan ini?')) {
                fetch('{{ route("dospem.bimbingan.reject", ":id") }}'.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.status === 'success') {
                        const jadwal = window.allJadwalEvents.find(j => j.id === id);
                        if (jadwal) jadwal.status = 'rejected';
                        renderJadwalList();
                        if (window.jadwalCalendar) window.jadwalCalendar.refetchEvents();
                        alert('Jadwal bimbingan ditolak!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Real-time polling for jadwal bimbingan
        function refreshJadwalData() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("dospem.jadwal-bimbingan.data") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateJadwalStats(data.data);
                    updateJadwalTable(data.data.bimbingan);
                }
            })
            .catch(error => console.error('Error fetching jadwal data:', error));
        }

        function updateJadwalStats(data) {
            if (document.getElementById('dospemMahasiswaAktif')) {
                document.getElementById('dospemMahasiswaAktif').textContent = data.mahasiswaAktifCount || 0;
            }
            if (document.getElementById('dospemTugasReview')) {
                document.getElementById('dospemTugasReview').textContent = data.tugasReview || 0;
            }
        }

        function updateJadwalTable(jadwalData) {
            window.allJadwalEvents = jadwalData;
            renderJadwalList();
            
            // Refresh calendar if it exists
            if (window.jadwalCalendar) {
                window.jadwalCalendar.refetchEvents();
            }
        }

        // Start real-time polling on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initial data load
            refreshJadwalData();
            
            // Set up polling interval (15 seconds)
            setInterval(refreshJadwalData, 15000);

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                
                    events: function(info, successCallback, failureCallback) {
                    fetch('{{ route("dospem.jadwal-bimbingan.events") }}')
                        .then(response => response.json())
                        .then(data => {
                            window.allJadwalEvents = data;
                            const events = data.map(event => ({
                                id: event.id,
                                title: event.title || event.topik || 'Bimbingan',
                                start: event.start || event.tanggal + 'T' + (event.waktu_mulai || '10:00'),
                                end: event.end || event.tanggal + 'T' + (event.waktu_selesai || '11:00'),
                                extendedProps: {
                                    status: event.status,
                                    mahasiswa: event.mahasiswa_name
                                },
                                backgroundColor: event.status === 'approved' ? '#10b981' : '#f59e0b',
                                borderColor: event.status === 'approved' ? '#059669' : '#d97706'
                            }));
                            successCallback(events);
                        })
                        .catch(error => {
                            console.error('Error loading events:', error);
                            failureCallback(error);
                        });
                },

                eventClick: function(info) {
                    viewEventDetails(info.event.id);
                }
            });
            calendar.render();
            // expose calendar instance globally for AJAX modal to refetch events
            window.jadwalCalendar = calendar;
        });

        // Close modal on background click
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEventModal();
            }
        });
    </script>
</body>
</html>

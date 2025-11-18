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
    @php
        $mahasiswaAktifCount = 15; // Dummy data
        $tugasReview = 5; // Dummy data
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dospem.partials.sidebar-dospem')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Jadwal Bimbingan</h3>
                        <div id="calendar"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                
                // === TAHAP 2: INTERACTIVITY ===
                
                // 1. Mengambil data dari backend

                // 2. Mengaktifkan interaksi
                selectable: true,
                editable: true,

                // 3. Handler untuk menambah jadwal baru
                select: function(info) {
                    const title = prompt('Masukkan Judul Jadwal:');
                    if (title) {
                        fetch('{{ route("dospem.jadwal-bimbingan.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                title: title,
                                start: info.startStr,
                                end: info.endStr
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.id) {
                                calendar.refetchEvents(); // Muat ulang data
                            }
                        });
                    }
                    calendar.unselect();
                },

                // 4. Handler untuk update jadwal (drag & drop)
                eventDrop: function(info) {
                    const event = info.event;
                    fetch('{{ route("dospem.jadwal-bimbingan.update", ":id") }}'.replace(':id', event.id), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            title: event.title,
                            start: event.startStr,
                            end: event.endStr
                        })
                    });
                },

                // 5. Handler untuk menghapus jadwal (klik)
                eventClick: function(info) {
                    if (confirm("Apakah Anda yakin ingin menghapus jadwal '" + info.event.title + "'?")) {
                        fetch('{{ route("dospem.jadwal-bimbingan.destroy", ":id") }}'.replace(':id', info.event.id), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.status === 'success') {
                                info.event.remove(); // Hapus dari tampilan
                            }
                        });
                    }
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>

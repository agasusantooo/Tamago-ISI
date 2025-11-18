<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Bimbingan - Tamago ISI</title>
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
</head>
<body class="bg-blue-50 text-gray-800">
    <?php
        $mahasiswaAktifCount = 15; // Dummy data
        $tugasReview = 5; // Dummy data
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('dospem.partials.sidebar-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('dospem.partials.header-dospem', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                        fetch('<?php echo e(route("dospem.jadwal-bimbingan.store")); ?>', {
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
                    fetch('<?php echo e(route("dospem.jadwal-bimbingan.update", ":id")); ?>'.replace(':id', event.id), {
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
                        fetch('<?php echo e(route("dospem.jadwal-bimbingan.destroy", ":id")); ?>'.replace(':id', info.event.id), {
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
<?php /**PATH C:\laragon\www\tam\ooo\Tamago-ISI-main\resources\views/dospem/jadwal-bimbingan.blade.php ENDPATH**/ ?>
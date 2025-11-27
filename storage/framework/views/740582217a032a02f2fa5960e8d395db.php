<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Acara - Tamago ISI</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
</head>
<body class="bg-green-50 text-gray-800">
    <?php
        $mahasiswaAktifCount = 150; 
        $tugasReview = 25; 
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('koordinator_ta.partials.sidebar-koordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('koordinator_ta.partials.header-koordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Jadwal Story Conference & TEFA Fair</h3>
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
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: '<?php echo e(route("koordinator_ta.jadwal.events")); ?>', // Route to fetch events
                selectable: true,
                editable: true,

                select: function(info) {
                    const title = prompt('Masukkan Nama Acara:');
                    if (title) {
                        // Simple color picker for demo
                        const color = prompt("Masukkan warna (contoh: '#3788d8' untuk biru, '#f0ad4e' untuk kuning):", '#3788d8');
                        
                        fetch('<?php echo e(route("koordinator_ta.jadwal.store")); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ title, start: info.startStr, end: info.endStr, color })
                        })
                        .then(response => response.json())
                        .then(() => calendar.refetchEvents());
                    }
                    calendar.unselect();
                },

                eventDrop: function(info) {
                    const event = info.event;
                    fetch(`/koordinator-ta/jadwal/events/${event.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ title: event.title, start: event.startStr, end: event.endStr, color: event.backgroundColor })
                    });
                },

                eventClick: function(info) {
                    if (confirm("Apakah Anda yakin ingin menghapus acara '" + info.event.title + "'?")) {
                        fetch(`/koordinator-ta/jadwal/events/${info.event.id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                        })
                        .then(() => info.event.remove());
                    }
                },

                eventDidMount: function(info) {
                    if (info.event.extendedProps.color) {
                        info.el.style.backgroundColor = info.event.extendedProps.color;
                        info.el.style.borderColor = info.event.extendedProps.color;
                    }
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
<?php /**PATH D:\C\Tamago-ISI\resources\views/koordinator_ta/jadwal.blade.php ENDPATH**/ ?>
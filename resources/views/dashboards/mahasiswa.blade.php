<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
                transition: transform 0.3s ease-in-out;
            }
            #sidebar.hidden-mobile {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- ✅ Sidebar Partial -->
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <!-- ✅ Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- ✅ Header Partial -->
            @include('mahasiswa.partials.header-mahasiswa')

            <!-- ✅ Main Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    Selamat datang, <span class="uppercase">{{ Auth::user()->name }}</span> 👋
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">NIM: {{ Auth::user()->mahasiswa->nim ?? 'N/A' }}</p>
                            </div>

                            <!-- Progres Tugas Akhir -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800">Progres Tugas Akhir</h3>
                                        <button onclick="toggleProgressDetail()" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                                            <i class="fas fa-list mr-1"></i>Detail
                                        </button>
                                    </div>
                                    @php
                                        $percentage = isset($progress) ? round($progress) : 0;
                                        $details = $progressDetails ?? [];
                                        $totalStages = $totalStages ?? count($details) ?: 1;

                                        // determine current stage: prefer 'Berlangsung', else last 'Selesai', else first
                                        $currentStage = null;
                                        foreach ($details as $d) {
                                            if (isset($d['status']) && strtolower($d['status']) === 'berlangsung') {
                                                $currentStage = $d['stage'];
                                                break;
                                            }
                                        }
                                        if (!$currentStage) {
                                            $lastDone = null;
                                            foreach ($details as $d) {
                                                if (isset($d['status']) && strtolower($d['status']) === 'selesai') {
                                                    $lastDone = $d['stage'];
                                                }
                                            }
                                            $currentStage = $lastDone ?? ($details[0]['stage'] ?? '—');
                                        }
                                    @endphp

                                    <div class="flex justify-center items-center">
                                        <div id="progressCard" class="relative w-40 h-40" data-percentage="{{ $percentage }}" data-total-stages="{{ $totalStages }}">
                                            <svg id="progressSvg" class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                                                <circle id="progressCircle" cx="50" cy="50" r="45" fill="none" stroke="#FACC15" stroke-width="8"
                                                    stroke-dasharray="282.743" stroke-dashoffset="282.743" stroke-linecap="round"/>
                                            </svg>
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-2">
                                                <span id="progressPercent" class="text-3xl font-bold text-yellow-600">{{ $percentage }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Legend progress dihapus, hanya persentase yang tampil -->
                                </div>

                                <!-- Dosen -->
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dosen Pembimbing</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full font-bold">
                                                SW
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">Dr. Sarah Wijaya, S.Kom., M.T.</p>
                                                <p class="text-xs text-gray-500">Pembimbing Utama</p>
                                            </div>
                                        </div>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center space-x-2 text-gray-600">
                                                <i class="fas fa-envelope w-4"></i>
                                                <span>sarah.wijaya@isi.ac.id</span>
                                            </div>
                                            <div class="flex items-center space-x-2 text-gray-600">
                                                <i class="fas fa-phone w-4"></i>
                                                <span>+62 812-3456-7890</span>
                                            </div>
                                        </div>
                                        <button class="w-full mt-3 px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition">
                                        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="w-full mt-3 px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition text-center block">
                                            <i class="fas fa-calendar-alt mr-2"></i>Buat Jadwal Bimbingan
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas & Deadline -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tugas & Deadline Mendatang</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Upload Revisi Proposal</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                <i class="far fa-clock mr-1"></i>Deadline: 25 Mar 2024, 23:59
                                            </p>
                                        </div>
                                        <a href="{{ route('mahasiswa.proposal.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                            Kerjakan
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-yellow-100 border-l-4 border-yellow-600 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Pendaftaran Story Conference</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                <i class="far fa-clock mr-1"></i>Deadline: 30 Mar 2024, 17:00
                                            </p>
                                        </div>
                                        <a href="{{ route('mahasiswa.story-conference.index') }}" class="px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-lg hover:bg-yellow-500">
                                            Daftar
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">Submit Laporan Bulanan</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                <i class="far fa-calendar mr-1"></i>Jadwal: 28 Mar 2024, 10:00
                                            </p>
                                        </div>
                                        <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg opacity-50 cursor-not-allowed" tabindex="-1" aria-disabled="true">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman Terbaru</h3>
                                <div class="space-y-3">
                                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Jadwal Seminar Proposal</p>
                                        <p class="text-xs text-gray-600 mt-1">16 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Dr. Ahmad Rahman</p>
                                    </div>
                                    <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Perpanjangan Deadline</p>
                                        <p class="text-xs text-gray-600 mt-1">20 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Prodi Film & TV</p>
                                    </div>
                                    <div class="p-4 bg-yellow-200 border-l-4 border-yellow-600 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">Workshop Produksi</p>
                                        <p class="text-xs text-gray-600 mt-1">22 September 2024</p>
                                        <p class="text-xs text-gray-500 mt-1">Studio Production</p>
                                    </div>
                                </div>
                                <button class="mt-4 w-full px-4 py-2 text-sm font-medium text-gray-800 bg-yellow-400 rounded-lg hover:bg-yellow-500 transition">
                                    Lihat Semua Pengumuman
                                </button>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Cepat</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Bimbingan</span>
                                        <span class="text-lg font-bold text-yellow-600">12</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Tugas Selesai</span>
                                        <span class="text-lg font-bold text-yellow-700">8/10</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">File Terupload</span>
                                        <span class="text-lg font-bold text-yellow-500">23</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("hidden-mobile");
        });
    </script>
    <script>
        // Animate progress circle and wire legend interactions
        (function(){
            const progressCard = document.getElementById('progressCard');
            if (!progressCard) return;

            const percentage = parseFloat(progressCard.dataset.percentage) || 0;
            const totalStages = parseInt(progressCard.dataset.totalStages) || 1;
            const circle = document.getElementById('progressCircle');
            const percentEl = document.getElementById('progressPercent');
            const stageEl = document.getElementById('progressStage');

            const R = 45;
            const C = 2 * Math.PI * R; // circumference ~282.743
            circle.setAttribute('stroke-dasharray', C.toFixed(3));

            // animate from full to target offset
            const targetOffset = Math.max(0, C * (1 - (percentage/100)));
            const duration = 800;
            const start = performance.now();
            const initial = C;

            function animate(now) {
                const t = Math.min(1, (now - start) / duration);
                const eased = t < 0.5 ? 2*t*t : -1 + (4 - 2*t)*t; // ease
                const current = initial + (targetOffset - initial) * eased;
                circle.setAttribute('stroke-dashoffset', current.toFixed(3));
                if (t < 1) requestAnimationFrame(animate);
            }
            requestAnimationFrame(animate);

            // Legend interactions
            const legend = document.getElementById('progressLegend');
            if (!legend) return;
            const items = Array.from(legend.querySelectorAll('.progress-legend-item'));

            function setActiveLegend(index) {
                items.forEach((it, i) => {
                    it.classList.toggle('bg-gray-50', i === index);
                    const dot = it.querySelector('span.w-2');
                    const label = it.querySelector('span:nth-child(2)');
                    if (i === index) {
                        dot.classList.remove('bg-gray-300');
                        dot.classList.add('bg-yellow-500');
                        label.classList.remove('text-gray-500');
                        label.classList.add('text-yellow-600','font-medium');
                    } else {
                        // restore based on original 'legend-active' class
                        if (it.classList.contains('legend-active')) {
                            dot.classList.remove('bg-gray-300');
                            dot.classList.add('bg-yellow-500');
                            label.classList.remove('text-gray-500');
                            label.classList.add('text-yellow-600','font-medium');
                        } else {
                            dot.classList.remove('bg-yellow-500');
                            dot.classList.add('bg-gray-300');
                            label.classList.remove('text-yellow-600','font-medium');
                            label.classList.add('text-gray-500');
                        }
                    }
                });

                // update center text with stage and approximate percent for this stage
                const stageName = items[index].querySelector('span:nth-child(2)').textContent.trim();
                const stagePercent = Math.min(100, Math.round(((index+1)/totalStages) * 100));
                percentEl.textContent = stagePercent + '%';
                stageEl.textContent = stageName;

                // also update header progress if present
                const headerBar = document.getElementById('headerProgressBarInner');
                const headerText = document.getElementById('headerProgressText');
                if (headerBar && headerText) {
                    headerBar.style.width = stagePercent + '%';
                    headerText.textContent = stagePercent + '%';
                }

                // animate ring to stagePercent
                const newTarget = Math.max(0, C * (1 - (stagePercent/100)));
                const animStart = performance.now();
                const from = parseFloat(circle.getAttribute('stroke-dashoffset')) || C;
                function animateTo(now) {
                    const tt = Math.min(1, (now - animStart) / 500);
                    const ev = tt < 0.5 ? 2*tt*tt : -1 + (4 - 2*tt)*tt;
                    const curr = from + (newTarget - from) * ev;
                    circle.setAttribute('stroke-dashoffset', curr.toFixed(3));
                    if (tt < 1) requestAnimationFrame(animateTo);
                }
                requestAnimationFrame(animateTo);
            }

            items.forEach((it, idx) => {
                it.addEventListener('click', () => setActiveLegend(idx));
            });

            // initial: show overall progress
            percentEl.textContent = percentage + '%';
            // update header to overall
            const headerBarInit = document.getElementById('headerProgressBarInner');
            const headerTextInit = document.getElementById('headerProgressText');
            if (headerBarInit && headerTextInit) {
                headerBarInit.style.width = percentage + '%';
                headerTextInit.textContent = percentage + '%';
            }
            // no change to stageEl (server computed)
        })();
    </script>
    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.emit('updateProgress', {{ $percentage }});
        });
    </script>
</body>
</html>

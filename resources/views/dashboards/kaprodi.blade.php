@extends('kaprodi.layouts.app')

@section('title', 'Dashboard Koordinator Prodi')

@section('content')
    <!-- Statistik Global TA -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Statistik Global TA</h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg">Semester Ini</button>
                <button class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg">Tahun Ini</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Mahasiswa TA</p>
                        <p id="totalMahasiswa" class="text-4xl font-bold text-blue-600">{{ $totalMahasiswa }}</p>
                    </div>
                    <i class="fas fa-users text-blue-600 text-3xl"></i>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Lulus</p>
                        <p id="mahasiswaLulus" class="text-4xl font-bold text-green-600">{{ $mahasiswaLulus }}</p>
                        <p id="mahasiswaLulusPct" class="text-xs text-green-600">{{ $totalMahasiswa ? round(($mahasiswaLulus/$totalMahasiswa)*100, 1) : 0 }}% dari total</p>
                    </div>
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-6 border-l-4 border-orange-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Belum Lulus</p>
                        <p id="belumLulus" class="text-4xl font-bold text-orange-600">{{ $belumLulus }}</p>
                        <p id="belumLulusPct" class="text-xs text-orange-600">{{ $totalMahasiswa ? round(($belumLulus/$totalMahasiswa)*100, 1) : 0 }}% dari total</p>
                    </div>
                    <i class="fas fa-hourglass-half text-orange-600 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Status per Semester</h4>
                <canvas id="semesterChart"></canvas>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Rata-rata Durasi TA</h4>
                <div class="space-y-4">
                    <div id="rataDurasiContainer">
                        @foreach($rataDurasiTA as $item)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">{{ $item['semester'] }}</span>
                                <span class="font-bold text-gray-800">{{ $item['durasi'] }} bulan</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($item['durasi']/12)*100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="pt-4 border-t">
                        <p class="text-center text-sm text-gray-600">Rata-rata Keseluruhan: <span class="font-bold text-blue-600">8.4 bulan</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Alert & Peringatan</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Deadline Pendaftaran</p>
                            <p class="text-sm text-gray-600 mt-1">Pendaftaran TA semester baru berakhir dalam 3 hari</p>
                            <button class="text-xs text-red-600 font-medium mt-2">Prioritas Tinggi</button>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-lg">
                        <i class="fas fa-clock text-orange-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Sidang Tertunda</p>
                            <p class="text-sm text-gray-600 mt-1">19 mahasiswa belum dijadwalkan sidang</p>
                            <button class="text-xs text-orange-600 font-medium mt-2">Perlu Tindakan</button>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                        <i class="fas fa-user-clock text-yellow-600 mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Pembimbing Tidak Aktif</p>
                            <p class="text-sm text-gray-600 mt-1">3 dosen pembimbing belum memberikan feedback</p>
                            <button class="text-xs text-yellow-600 font-medium mt-2">Monitoring</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman Penting</h3>
                <div class="space-y-3">
                    <div id="pengumumanContainer">
                        @forelse($pengumumanPenting as $item)
                        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                            <p class="font-semibold text-gray-800 text-sm">{{ $item->judul }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $item->tanggal }}</p>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada pengumuman</p>
                        @endforelse
                    </div>
                    <button class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Buat Pengumuman Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Status per Semester
        const ctx = document.getElementById('semesterChart');
        let semesterChart = null;
        function initChart(labels, lulusData, belumData) {
            if (!ctx) return;
            const config = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Lulus',
                        data: lulusData,
                        backgroundColor: '#10b981',
                    }, {
                        label: 'Belum Lulus',
                        data: belumData,
                        backgroundColor: '#f59e0b',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: { y: { beginAtZero: true } }
                }
            };

            if (semesterChart) {
                semesterChart.destroy();
            }
            semesterChart = new Chart(ctx, config);
        }

        // Initialize with server-rendered values
        initChart({!! json_encode($chartData['labels']) !!}, {!! json_encode($chartData['lulus']) !!}, {!! json_encode($chartData['belum_lulus']) !!});

        // Polling function to refresh dashboard data
        async function fetchDashboardData() {
            try {
                const res = await fetch("{{ route('kaprodi.dashboard.data') }}", { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();

                // Update simple stats
                document.getElementById('totalMahasiswa').textContent = data.totalMahasiswa ?? 0;
                document.getElementById('mahasiswaLulus').textContent = data.mahasiswaLulus ?? 0;
                const pctLulus = data.totalMahasiswa ? Math.round((data.mahasiswaLulus/data.totalMahasiswa)*1000)/10 : 0;
                document.getElementById('mahasiswaLulusPct').textContent = pctLulus + '% dari total';
                document.getElementById('belumLulus').textContent = data.belumLulus ?? 0;
                const pctBelum = data.totalMahasiswa ? Math.round((data.belumLulus/data.totalMahasiswa)*1000)/10 : 0;
                document.getElementById('belumLulusPct').textContent = pctBelum + '% dari total';

                // Update chart
                if (semesterChart && data.chartData) {
                    semesterChart.data.labels = data.chartData.labels;
                    semesterChart.data.datasets[0].data = data.chartData.lulus;
                    semesterChart.data.datasets[1].data = data.chartData.belum_lulus;
                    semesterChart.update();
                }

                // Update rata-rata durasi
                const rataContainer = document.getElementById('rataDurasiContainer');
                if (rataContainer && data.rataDurasiTA) {
                    rataContainer.innerHTML = '';
                    data.rataDurasiTA.forEach(function(item) {
                        const div = document.createElement('div');
                        div.innerHTML = `
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">${item.semester}</span>
                                <span class="font-bold text-gray-800">${item.durasi} bulan</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: ${ (item.durasi/12)*100 }%"></div>
                            </div>
                        `;
                        rataContainer.appendChild(div);
                    });
                }

                // Update pengumuman
                const pengContainer = document.getElementById('pengumumanContainer');
                if (pengContainer && data.pengumumanPenting) {
                    pengContainer.innerHTML = '';
                    if (data.pengumumanPenting.length === 0) {
                        pengContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada pengumuman</p>';
                    } else {
                        data.pengumumanPenting.forEach(function(item) {
                            const d = document.createElement('div');
                            d.className = 'p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg';
                            d.innerHTML = `<p class="font-semibold text-gray-800 text-sm">${item.judul}</p><p class="text-xs text-gray-600 mt-1">${item.tanggal}</p>`;
                            pengContainer.appendChild(d);
                        });
                    }
                }

                // Update header counters (if present on the page)
                try {
                    const hdrMah = document.getElementById('headerMahasiswaAktif');
                    if (hdrMah) hdrMah.textContent = (data.mahasiswaAktifCount ?? data.totalMahasiswa ?? 0);
                    const hdrTugas = document.getElementById('headerTugasReview');
                    if (hdrTugas) hdrTugas.textContent = (data.tugasReview ?? 0);
                } catch (e) {
                    // ignore DOM errors
                }

            } catch (e) {
                console.error('Failed to fetch dashboard data', e);
            }
        }

        // Poll every 10 seconds
        setInterval(fetchDashboardData, 10000);
    </script>
@endsection
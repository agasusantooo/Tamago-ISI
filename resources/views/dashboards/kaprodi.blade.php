<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator Prodi - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col" id="sidebar">
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-900 text-white flex items-center justify-center rounded-lg font-bold text-xs">
                        ISI<br>YK
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                        <p class="text-xs text-gray-500">Koordinator Prodi</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'text-purple-900 bg-purple-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition">
                            <i class="fas fa-home w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kaprodi.statistik') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>Statistik Global TA</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kaprodi.manajemen-data') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-database w-5"></i>
                            <span>Manajemen Data</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-file-excel w-5"></i>
                            <span>Manajemen Syarat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kaprodi.monitoring') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-eye w-5"></i>
                            <span>Monitoring Progress</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-calendar w-5"></i>
                            <span>Setup Semester</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-user w-5"></i>
                            <span>Akun</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-purple-600 text-white flex items-center justify-center rounded-full font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate uppercase">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Koordinator Prodi</h2>
                    <div class="flex items-center space-x-4">
                        <p class="text-sm font-semibold uppercase">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
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
                                        <p class="text-4xl font-bold text-blue-600">{{ $totalMahasiswa }}</p>
                                    </div>
                                    <i class="fas fa-users text-blue-600 text-3xl"></i>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Lulus</p>
                                        <p class="text-4xl font-bold text-green-600">{{ $mahasiswaLulus }}</p>
                                        <p class="text-xs text-green-600">{{ round(($mahasiswaLulus/$totalMahasiswa)*100, 1) }}% dari total</p>
                                    </div>
                                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                                </div>
                            </div>

                            <div class="bg-orange-50 rounded-lg p-6 border-l-4 border-orange-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Belum Lulus</p>
                                        <p class="text-4xl font-bold text-orange-600">{{ $belumLulus }}</p>
                                        <p class="text-xs text-orange-600">{{ round(($belumLulus/$totalMahasiswa)*100, 1) }}% dari total</p>
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
                                    @forelse($pengumumanPenting as $item)
                                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $item->judul }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $item->tanggal }}</p>
                                    </div>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">Tidak ada pengumuman</p>
                                    @endforelse
                                    <button class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Buat Pengumuman Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Chart Status per Semester
        const ctx = document.getElementById('semesterChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'Lulus',
                    data: {!! json_encode($chartData['lulus']) !!},
                    backgroundColor: '#10b981',
                }, {
                    label: 'Belum Lulus',
                    data: {!! json_encode($chartData['belum_lulus']) !!},
                    backgroundColor: '#f59e0b',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>
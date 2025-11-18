<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kaprodi - Tamago ISI</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-teal-50 text-gray-800">
    <?php
        // Dummy data untuk menghindari error di header
        $mahasiswaAktifCount = 250; 
        $tugasReview = 40; 
    ?>
    <div class="flex h-screen overflow-hidden">

        <?php echo $__env->make('kaprodi.partials.sidebar-kaprodi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <?php echo $__env->make('kaprodi.partials.header-kaprodi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-6xl mx-auto space-y-6">
                    <h3 class="text-lg font-semibold text-teal-800 mb-4">Dashboard Kaprodi</h3>
                    <p>Selamat datang di dashboard Koordinator Program Studi. Berikut adalah ringkasan progres mahasiswa secara global.</p>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Ringkasan Statistik -->
                        <div class="md:col-span-3 flex flex-col">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-1">
                                <div class="bg-white rounded-xl shadow-sm p-6 text-center h-full flex flex-col justify-center items-center">
                                    <p class="text-sm text-gray-500">Total Mahasiswa TA</p>
                                    <p class="text-3xl font-bold text-teal-600 mt-2">250</p>
                                </div>
                                <div class="bg-white rounded-xl shadow-sm p-6 text-center h-full flex flex-col justify-center items-center">
                                    <p class="text-sm text-gray-500">Tahap Proposal</p>
                                    <p class="text-3xl font-bold text-cyan-600 mt-2">120</p>
                                </div>
                                <div class="bg-white rounded-xl shadow-sm p-6 text-center h-full flex flex-col justify-center items-center">
                                    <p class="text-sm text-gray-500">Tahap Produksi</p>
                                    <p class="text-3xl font-bold text-teal-600 mt-2">80</p>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Progres Mahasiswa -->
                        <div class="md:col-span-1">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h4 class="text-md font-semibold text-gray-800 mb-3">Distribusi Progres Tahapan</h4>
                                <div class="max-w-xs mx-auto">
                                    <canvas id="progressChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Ringkasan Progres -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-3">Ringkasan Progres per Tahapan</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-teal-50 border-b border-teal-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-teal-700">Tahapan</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-teal-700">Jumlah Mahasiswa</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-teal-700">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-teal-50">
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">Proposal</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">120</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">48%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">Pra-Produksi</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">50</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">20%</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">Produksi</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">80</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-600">32%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart').getContext('2d');
            const progressChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Proposal', 'Pra-Produksi', 'Produksi'],
                    datasets: [{
                        data: [120, 50, 80], // Dummy data
                        backgroundColor: [
                            '#14b8a6', // teal-500
                            '#22d3ee', // cyan-400
                            '#0d9488'  // teal-600
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom', // Changed from 'top' to 'bottom'
                        },
                        title: {
                            display: false,
                            text: 'Distribusi Progres Tahapan'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html><?php /**PATH D:\Tamago-ISI\resources\views/kaprodi/dashboard.blade.php ENDPATH**/ ?>
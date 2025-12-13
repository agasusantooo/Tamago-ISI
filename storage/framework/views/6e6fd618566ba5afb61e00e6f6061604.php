<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard Dosen Penguji - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</head>
<body class="bg-purple-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <?php echo $__env->make('dosen_penguji.partials.sidebar-dosen_penguji', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php echo $__env->make('dosen_penguji.partials.header-dosen_penguji', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-purple-900">Dashboard Dosen Penguji</h1>
                        <p class="text-gray-600 mt-2">Kelola ujian dan penilaian mahasiswa</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Total Ujian -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Total Ujian</p>
                                    <p class="text-2xl font-bold text-purple-900" id="ujianTotal"><?php echo e($ujianTotal ?? 0); ?></p>
                                </div>
                                <i class="fas fa-graduation-cap text-purple-600 text-3xl opacity-50"></i>
                            </div>
                        </div>

                        <!-- Menunggu Nilai -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Menunggu Nilai</p>
                                    <p class="text-2xl font-bold text-yellow-900" id="ujianMenungguNilai"><?php echo e($ujianMenungguNilai ?? 0); ?></p>
                                </div>
                                <i class="fas fa-hourglass text-yellow-600 text-3xl opacity-50"></i>
                            </div>
                        </div>

                        <!-- Ujian Mendatang -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Ujian Mendatang</p>
                                    <p class="text-2xl font-bold text-blue-900" id="ujianMendatang"><?php echo e($ujianMendatang ?? 0); ?></p>
                                </div>
                                <i class="fas fa-calendar text-blue-600 text-3xl opacity-50"></i>
                            </div>
                        </div>

                        <!-- Rata-Rata Nilai -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Rata-Rata Nilai</p>
                                    <p class="text-2xl font-bold text-green-900" id="rataRataNilai"><?php echo e($rataRataNilai ?? 0); ?></p>
                                </div>
                                <i class="fas fa-chart-line text-green-600 text-3xl opacity-50"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Chart Section -->
                        <div class="lg:col-span-1 bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4">Status Ujian</h3>
                            <canvas id="statusChart"></canvas>
                        </div>

                        <!-- Ujian List Section -->
                        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4">Daftar Ujian TA</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-left py-3 px-2 text-gray-600">Mahasiswa</th>
                                            <th class="text-left py-3 px-2 text-gray-600">Tanggal Ujian</th>
                                            <th class="text-left py-3 px-2 text-gray-600">Status</th>
                                            <th class="text-left py-3 px-2 text-gray-600">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ujianTableBody">
                                        <?php $__empty_1 = true; $__currentLoopData = $ujianList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ujian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-2">
                                                <div class="font-medium"><?php echo e($ujian['mahasiswa']); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo e($ujian['nim']); ?></div>
                                            </td>
                                            <td class="py-3 px-2"><?php echo e($ujian['jadwal']); ?></td>
                                            <td class="py-3 px-2">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                    <?php if($ujian['status'] == 'selesai'): ?> bg-green-100 text-green-800
                                                    <?php elseif($ujian['status'] == 'berlangsung'): ?> bg-blue-100 text-blue-800
                                                    <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $ujian['status']))); ?>

                                                </span>
                                            </td>
                                            <td class="py-3 px-2 font-semibold"><?php echo e($ujian['nilai'] ?? '-'); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr class="border-b">
                                            <td colspan="4" class="py-6 text-center text-gray-500">
                                                <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                                                Tidak ada ujian
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        let chartInstance = null;

        function initChart(data) {
            const ctx = document.getElementById('statusChart');
            if (!ctx) return;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.chartData.labels,
                    datasets: [{
                        data: data.chartData.data,
                        backgroundColor: ['#FCD34D', '#10B981', '#8B5CF6'],
                        borderColor: ['#F59E0B', '#059669', '#7C3AED'],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        function refreshData() {
            fetch('<?php echo e(route("dosen_penguji.dashboard.data")); ?>', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    updateUI(data.data);
                }
            })
            .catch(err => console.error('Error refreshing data:', err));
        }

        function updateUI(data) {
            // Update stats
            document.getElementById('ujianTotal').textContent = data.ujianTotal;
            document.getElementById('ujianMenungguNilai').textContent = data.ujianMenungguNilai;
            document.getElementById('ujianMendatang').textContent = data.ujianMendatang;
            document.getElementById('rataRataNilai').textContent = data.rataRataNilai;

            // Update chart
            initChart(data);

            // Update table
            updateTable(data.ujianList);
        }

        function updateTable(ujianList) {
            const tbody = document.getElementById('ujianTableBody');
            if (!tbody) return;

            if (ujianList.length === 0) {
                tbody.innerHTML = `
                    <tr class="border-b">
                        <td colspan="4" class="py-6 text-center text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                            Tidak ada ujian
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = ujianList.map(ujian => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-2">
                        <div class="font-medium">${ujian.mahasiswa}</div>
                        <div class="text-xs text-gray-500">${ujian.nim}</div>
                    </td>
                    <td class="py-3 px-2">${ujian.jadwal}</td>
                    <td class="py-3 px-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            ${ujian.status == 'selesai' ? 'bg-green-100 text-green-800' :
                              ujian.status == 'berlangsung' ? 'bg-blue-100 text-blue-800' :
                              'bg-yellow-100 text-yellow-800'}">
                            ${ujian.status.replace(/_/g, ' ').charAt(0).toUpperCase() + ujian.status.replace(/_/g, ' ').slice(1)}
                        </span>
                    </td>
                    <td class="py-3 px-2 font-semibold">${ujian.nilai || '-'}</td>
                </tr>
            `).join('');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            const initialData = {
                ujianTotal: <?php echo e($ujianTotal ?? 0); ?>,
                ujianMenungguNilai: <?php echo e($ujianMenungguNilai ?? 0); ?>,
                ujianMendatang: <?php echo e($ujianMendatang ?? 0); ?>,
                rataRataNilai: <?php echo e($rataRataNilai ?? 0); ?>,
                ujianList: <?php echo json_encode($ujianList ?? [], 15, 512) ?>,
                chartData: <?php echo json_encode($chartData ?? [], 15, 512) ?>
            };
            initChart(initialData);

            // Poll every 10 seconds for real-time updates
            setInterval(refreshData, 10000);
        });

        // Close modal when clicking outside
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                console.log('Escape pressed');
            }
        });
    </script>
</body>
</html><?php /**PATH D:\C\Tamago-ISI\resources\views/dosen_penguji/dashboard.blade.php ENDPATH**/ ?>
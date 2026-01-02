<?php $__env->startSection('content'); ?>
<div class="flex">
    
    <aside class="w-64 bg-white shadow-lg flex flex-col h-screen fixed left-0 top-0" id="sidebar">
        <div class="p-6 border-b">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-orange-900 text-white flex items-center justify-center rounded-lg font-bold text-xs">
                    ISI<br>YK
                </div>
                <div>
                    <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                    <p class="text-xs text-gray-500">Koordinator TA</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                <li>
                    <a href="<?php echo e(route('dashboard')); ?>"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition
                        <?php echo e(request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-900' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('dosen-penguji.ujian-mendatang')); ?>"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition
                        <?php echo e(request()->routeIs('dosen-penguji.ujian-mendatang') ? 'bg-orange-50 text-orange-900' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span>Ujian TA</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('dosen-penguji.penilaian')); ?>"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition
                        <?php echo e(request()->routeIs('dosen-penguji.penilaian') ? 'bg-orange-50 text-orange-900' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <i class="fas fa-star w-5"></i>
                        <span>Penilaian</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition
                        <?php echo e(request()->routeIs('dosen-penguji.riwayat') ? 'bg-orange-50 text-orange-900' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <i class="fas fa-history w-5"></i>
                        <span>Riwayat Penilaian</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition
                        <?php echo e(request()->routeIs('profile') ? 'bg-orange-50 text-orange-900' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <i class="fas fa-user w-5"></i>
                        <span>Akun Saya</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition
                            text-gray-700 hover:bg-red-50 hover:text-red-700">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 bg-orange-600 text-white flex items-center justify-center rounded-full font-semibold">
                    <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate uppercase"><?php echo e(Auth::user()->name); ?></p>
                    <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                </div>
            </div>
        </div>
    </aside>
    
    <div class="flex-1 ml-64 p-6 max-w-7xl">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Mahasiswa TA</p>
                        <p class="text-4xl font-bold text-blue-600"><?php echo e($totalMahasiswa); ?></p>
                        <p class="text-sm text-green-600 mt-1">↑ +12 dari semester lalu</p>
                    </div>
                    <i class="fas fa-users text-4xl text-blue-600"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Sudah Ujian</p>
                        <p class="text-4xl font-bold text-green-600"><?php echo e($sudahUjian); ?></p>
                        <p class="text-sm text-gray-600 mt-1">62.7% dari total</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-green-600"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Lulus</p>
                        <p class="text-4xl font-bold text-purple-600"><?php echo e($lulus); ?></p>
                        <p class="text-sm text-gray-600 mt-1">85.4% tingkat kelulusan</p>
                    </div>
                    <i class="fas fa-trophy text-4xl text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tugas Persetujuan -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Tugas Menunggu Persetujuan</h3>
                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm font-semibold">
                        <?php echo e($tugasMenungguPersetujuan); ?> Pending
                    </span>
                </div>
                <?php $__currentLoopData = $daftarPersetujuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3">
                    <div>
                        <p class="font-semibold text-gray-800"><?php echo e($tugas->jenis); ?></p>
                        <p class="text-sm text-gray-600">Mahasiswa: <?php echo e($tugas->mahasiswa_name); ?></p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">✓</button>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">✗</button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <!-- Jadwal Mendatang -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Jadwal Mendatang</h3>
                <?php $__currentLoopData = $jadwalMendatang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 border-l-4 border-<?php echo e($jadwal->color); ?>-500 bg-<?php echo e($jadwal->color); ?>-50 rounded-lg mb-3">
                    <div class="flex items-start">
                        <div class="text-center mr-4">
                            <p class="text-2xl font-bold text-gray-800"><?php echo e($jadwal->tanggal_numeric); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($jadwal->bulan); ?></p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800"><?php echo e($jadwal->nama); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($jadwal->waktu); ?> • <?php echo e($jadwal->lokasi); ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($jadwal->peserta); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\C\Tamago-ISI\resources\views\dashboards\dospenguji.blade.php ENDPATH**/ ?>
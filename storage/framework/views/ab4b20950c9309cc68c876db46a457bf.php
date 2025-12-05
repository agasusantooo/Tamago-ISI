<!-- Sidebar -->
<aside class="w-64 bg-white flex flex-col" id="sidebar">
    <!-- Logo -->
    <div class="p-6">
        <div class="flex items-center space-x-3">
            <img src="/images/logo-isi.png" 
                 alt="Logo ISI" 
                 class="w-12 h-12 object-contain rounded-lg">
            <div>
                <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                <p class="text-xs text-gray-500">Koordinator TA</p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="<?php echo e(route('koordinator_ta.dashboard')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition <?php echo e(request()->routeIs('koordinator_ta.dashboard') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-50'); ?>">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('koordinator_ta.jadwal')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('koordinator_ta.jadwal') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-50'); ?>">
                    <i class="fas fa-calendar-alt w-5"></i><span>Jadwal Kegiatan</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('koordinator_ta.monitoring')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('koordinator_ta.monitoring') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-50'); ?>">
                    <i class="fas fa-tv w-5"></i><span>Monitoring Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('koordinator_ta.matakuliah')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('koordinator_ta.matakuliah') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-50'); ?>">
                    <i class="fas fa-book w-5"></i><span>Pengaturan Matkul</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('koordinator_ta.profile')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('koordinator_ta.profile') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-50'); ?>">
                    <i class="fas fa-user w-5"></i><span>Akun Saya</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
    </div>
</aside><?php /**PATH D:\Tamago-ISI\resources\views/koordinator_ta/partials/sidebar-koordinator.blade.php ENDPATH**/ ?>
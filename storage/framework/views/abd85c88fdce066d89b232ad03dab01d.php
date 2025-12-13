<!-- Sidebar -->
<aside class="w-64 bg-white shadow-md flex flex-col" id="sidebar">
    <!-- Logo -->
    <div class="p-6">
        <div class="flex items-center space-x-3">
            <img src="<?php echo e(asset('images/logo-isi.png')); ?>" 
                 alt="Logo ISI" 
                 class="w-12 h-12 object-contain rounded-lg">
            <div>
                <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                <p class="text-xs text-gray-500">Sistem TA</p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="<?php echo e(route('dashboard')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition 
                   <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('dospem.mahasiswa-bimbingan')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   <?php echo e(request()->routeIs('dospem.mahasiswa-bimbingan') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                    <i class="fas fa-users w-5"></i><span>Mahasiswa Bimbingan</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('dospem.jadwal-bimbingan')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   <?php echo e(request()->routeIs('dospem.jadwal-bimbingan') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                    <i class="fas fa-calendar-alt w-5"></i><span>Jadwal Bimbingan</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('dospem.review-tugas')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   <?php echo e(request()->routeIs('dospem.review-tugas') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                    <i class="fas fa-tasks w-5"></i><span>Review Progress</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('dospem.profile')); ?>"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   <?php echo e(request()->routeIs('dospem.profile') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50'); ?>">
                    <i class="fas fa-user w-5"></i><span>Akun Saya</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
    </div>
</aside>
<?php /**PATH D:\C\Tamago-ISI\resources\views/dospem/partials/sidebar-dospem.blade.php ENDPATH**/ ?>
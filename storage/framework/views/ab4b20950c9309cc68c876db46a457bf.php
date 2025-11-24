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
                <a href="/koordinator-ta/dashboard"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition bg-green-100 text-green-700 font-semibold">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/koordinator-ta/jadwal"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-green-50">
                    <i class="fas fa-calendar-alt w-5"></i><span>Jadwal Acara</span>
                </a>
            </li>
            <li>
                <a href="/koordinator-ta/monitoring"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-green-50">
                    <i class="fas fa-tv w-5"></i><span>Monitoring Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="/koordinator-ta/matakuliah"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-green-50">
                    <i class="fas fa-book w-5"></i><span>Pengaturan Matkul</span>
                </a>
            </li>
            <li>
                <a href="/koordinator-ta/profile"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-green-50">
                    <i class="fas fa-user w-5"></i><span>Akun Saya</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4">
        <form method="POST" action="/logout">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
    </div>
</aside><?php /**PATH D:\Tamago-ISI\resources\views/koordinator_ta/partials/sidebar-koordinator.blade.php ENDPATH**/ ?>
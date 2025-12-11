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
                <p class="text-xs text-gray-500">Koordinator Story Conference</p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="{{ route('koordinator_story_conference.dashboard') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('koordinator_story_conference.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('koordinator_story_conference.monitoring') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('koordinator_story_conference.monitoring') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fas fa-tv w-5"></i><span>Monitoring Pendaftar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('koordinator_story_conference.jadwal.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('koordinator_story_conference.jadwal.index') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-blue-50' }}">
                    <i class="fas fa-calendar-alt w-5"></i><span>Jadwal Story Conference</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4">
        
    </div>
</aside>
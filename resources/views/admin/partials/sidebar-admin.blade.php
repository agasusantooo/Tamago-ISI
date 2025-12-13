<!-- Sidebar -->
<aside class="w-64 bg-white flex flex-col" id="sidebar">
    <!-- Logo -->
    <div class="p-6">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-isi.png') }}" 
                 alt="Logo ISI" 
                 class="w-12 h-12 object-contain rounded-lg">
            <div>
                <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-red-100 text-red-700 font-semibold' : 'text-gray-700 hover:bg-red-50' }}">
                    <i class="fas fa-cogs w-5"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition 
                   {{ request()->routeIs('admin.users') ? 'bg-red-100 text-red-700 font-semibold' : 'text-gray-700 hover:bg-red-50' }}">
                    <i class="fas fa-users w-5"></i><span>Users</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

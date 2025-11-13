<!-- Sidebar -->
<aside class="w-64 bg-white shadow-md flex flex-col" id="sidebar">
    <!-- Logo -->
    <div class="p-6">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-isi.png') }}" 
                 alt="Logo ISI" 
                 class="w-12 h-12 object-contain rounded-lg">
            <div>
                <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                <p class="text-xs text-gray-500">Kaprodi</p>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="{{ route('kaprodi.dashboard') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition 
                   {{ request()->routeIs('kaprodi.dashboard') ? 'bg-teal-100 text-teal-700 font-semibold' : 'text-gray-700 hover:bg-teal-50' }}">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kaprodi.setup') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('kaprodi.setup') ? 'bg-teal-100 text-teal-700 font-semibold' : 'text-gray-700 hover:bg-teal-50' }}">
                    <i class="fas fa-cogs w-5"></i><span>Setup & Timeline</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kaprodi.pengelolaan') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('kaprodi.pengelolaan') ? 'bg-teal-100 text-teal-700 font-semibold' : 'text-gray-700 hover:bg-teal-50' }}">
                    <i class="fas fa-sitemap w-5"></i><span>Pengelolaan</span>
                </a>
            </li>

            <li>
                <a href="#"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('kaprodi.profile') ? 'bg-teal-100 text-teal-700 font-semibold' : 'text-gray-700 hover:bg-teal-50' }}">
                    <i class="fas fa-user w-5"></i><span>Akun Saya</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
    </div>
</aside>

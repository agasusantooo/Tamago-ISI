<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col" id="sidebar">
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-red-900 text-white flex items-center justify-center rounded-lg font-bold text-xs">
                        ISI<br>YK
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            {{ request()->routeIs('dashboard') ? 'bg-red-50 text-red-900' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-home w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            {{ request()->routeIs('admin.users') ? 'bg-red-50 text-red-900' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Manajemen Akun</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            {{ request()->routeIs('admin.roles') ? 'bg-red-50 text-red-900' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-user-shield w-5"></i>
                            <span>Role & Permission</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logs') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            {{ request()->routeIs('admin.logs') ? 'bg-red-50 text-red-900' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-history w-5"></i>
                            <span>Log Aktivitas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-database w-5"></i>
                            <span>Backup Database</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            {{ request()->routeIs('admin.settings') ? 'bg-red-50 text-red-900' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-cog w-5"></i>
                            <span>Pengaturan Sistem</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition font-medium
                            text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user w-5"></i>
                            <span>Akun</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-red-600 text-white flex items-center justify-center rounded-full font-semibold">
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
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
                    <p class="text-sm font-semibold uppercase">{{ Auth::user()->name }}</p>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">

                    <!-- Statistik -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Pengguna</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-medium text-gray-600">Total Mahasiswa</h4>
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-medium text-gray-600">Total Dosen</h4>
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalDosen }}</p>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-medium text-gray-600">Total Korprodi</h4>
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalKorprodi }}</p>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-medium text-gray-600">Total Admin</h4>
                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-shield text-orange-600 text-xl"></i>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalAdmin }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas & Notifikasi -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terakhir Sistem</h3>
                            <div class="space-y-3">
                                @forelse($aktivitasTerakhir as $aktivitas)
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-sm font-medium">{{ $aktivitas->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $aktivitas->created_at }}</p>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Tidak ada aktivitas</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notifikasi Penting Sistem</h3>
                            <div class="space-y-3">
                                @forelse($notifikasiPenting as $notif)
                                    <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                        <p class="text-sm font-semibold">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-600">{{ $notif->message }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notif->created_at }}</p>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Semua notifikasi sudah dibaca</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>

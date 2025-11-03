<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen Pembimbing - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col" id="sidebar">
            <!-- Logo & Title -->
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-900 text-white flex items-center justify-center rounded-lg font-bold text-xs">
                        ISI<br>YK
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
                        <p class="text-xs text-gray-500">Dosen Pembimbing</p>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'text-blue-900 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition">
                            <i class="fas fa-home w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dospem.mahasiswa-bimbingan') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('dospem.mahasiswa-bimbingan') ? 'text-blue-900 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
                            <i class="fas fa-users w-5"></i>
                            <span>Mahasiswa Bimbingan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dospem.jadwal-bimbingan') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span>Jadwal Bimbingan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dospem.review-tugas') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-tasks w-5"></i>
                            <span>Review Tugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-file-alt w-5"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-user w-5"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-full font-semibold">
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
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Dosen Pembimbing</h2>
                    <div class="flex items-center space-x-4">
                        <p class="text-sm font-semibold uppercase">{{ Auth::user()->name }}</p>
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Mahasiswa Aktif</p>
                                    <p class="text-3xl font-bold text-blue-600">{{ $mahasiswaAktifCount }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Tugas Review</p>
                                    <p class="text-3xl font-bold text-orange-600">{{ $tugasReview }}</p>
                                </div>
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-orange-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Bimbingan Minggu Ini</p>
                                    <p class="text-3xl font-bold text-green-600">{{ $bimbinganMingguIni }}</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">TA Selesai</p>
                                    <p class="text-3xl font-bold text-purple-600">{{ $taSelesai }}</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-trophy text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Mahasiswa Bimbingan Aktif -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa Bimbingan Aktif</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 border-b">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nama Mahasiswa</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Judul TA</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Progress</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse($mahasiswaBimbingan as $mhs)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span class="font-bold text-blue-600">{{ strtoupper(substr($mhs->name, 0, 1)) }}</span>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-800">{{ $mhs->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $mhs->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <p class="text-sm text-gray-700">{{ $mhs->judul_ta ?? 'Belum ada judul' }}</p>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $mhs->progress }}%"></div>
                                                        </div>
                                                        <span class="text-sm font-bold text-blue-600">{{ $mhs->progress }}%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada mahasiswa bimbingan</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tugas Menunggu Review -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tugas Menunggu Review</h3>
                                <div class="space-y-3">
                                    @forelse($tugasMenungguReview as $tugas)
                                    <div class="flex items-center justify-between p-4 bg-orange-50 border-l-4 border-orange-500 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $tugas->judul }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $tugas->mahasiswa_name }}</p>
                                            <p class="text-xs text-orange-600 mt-1">Diajukan: {{ $tugas->created_at }}</p>
                                        </div>
                                        <button class="px-4 py-2 text-sm bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                            Review
                                        </button>
                                    </div>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">Tidak ada tugas yang perlu direview</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Jadwal Bimbingan Mendatang -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Bimbingan Mendatang</h3>
                                <div class="space-y-3">
                                    @forelse($jadwalBimbingan as $jadwal)
                                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $jadwal->mahasiswa_name }}</p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            <i class="far fa-calendar mr-1"></i>{{ $jadwal->tanggal }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $jadwal->topik }}</p>
                                    </div>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">Tidak ada jadwal bimbingan</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

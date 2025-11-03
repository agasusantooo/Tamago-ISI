<aside class="w-64 bg-white shadow-md flex flex-col hidden-mobile h-screen" id="sidebar">
    <div class="p-6 border-b flex items-center space-x-3">
        <!-- Ganti logo kotak biru dengan logo gambar -->
        <div class="flex items-center justify-center">
            <img src="{{ asset('images/logo-isi.png') }}" 
                 alt="Logo ISI" 
                 class="w-12 h-12 object-contain rounded-lg">
        </div>
        <div>
            <h1 class="font-bold text-lg text-gray-800">Tamago ISI</h1>
            <p class="text-xs text-gray-500">Sistem TA</p>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <li>
                <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('dashboard') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mahasiswa.proposal') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('mahasiswa.proposal') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Pengajuan Proposal</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mahasiswa.bimbingan') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('mahasiswa.bimbingan') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-chalkboard-teacher w-5"></i>
                    <span>Bimbingan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mahasiswa.story-conference') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('mahasiswa.story-conference') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-comments w-5"></i>
                    <span>Story Conference</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mahasiswa.produksi') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('mahasiswa.produksi') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-film w-5"></i>
                    <span>Produksi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mahasiswa.ujian-ta') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('mahasiswa.ujian-ta') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-graduation-cap w-5"></i>
                    <span>Ujian TA</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mahasiswa.naskah-karya') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('mahasiswa.naskah-karya') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-folder-open w-5"></i>
                    <span>Naskah & Karya</span>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.edit') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                {{ request()->routeIs('profile.edit') ? 'text-blue-900 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-user w-5"></i>
                    <span>Akun Saya</span>
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

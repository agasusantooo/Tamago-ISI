<header class="bg-white">
    <div class="flex items-center px-6 py-4">
        <div class="flex-1 mr-8 flex items-center space-x-3">
            <img src="{{ asset('images/logo-isi.png') }}" alt="Logo ISI" class="w-10 h-10 object-contain rounded-lg">
            <div>
                <h1 class="text-lg font-semibold text-gray-700">Koordinator Prodi</h1>
                <div class="mt-2 mb-1">
                    <p class="text-xs text-gray-500">Mahasiswa Aktif: <span class="font-semibold text-teal-600">{{ $mahasiswaAktifCount ?? 0 }}</span> | Tugas Review: <span class="font-semibold text-cyan-600">{{ $tugasReview ?? 0 }}</span></p>
                </div>
                <div class="h-3"></div>
            </div>
        </div>
    
        <div class="flex flex-col items-center">
            <img src="{{ asset('images/user.png') }}" alt="User Icon" class="w-9 h-9 rounded-full">
            <span class="text-gray-600 text-xs mt-1">{{ Auth::user()->name }}</span>
        </div>
    </div>
</header>

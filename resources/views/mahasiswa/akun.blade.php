@extends('mahasiswa.layouts.app')

@section('page-title', 'Progress Tugas Akhir')

@section('title', 'Akun - Tamago ISI')

@section('content')
    <div class="max-w-7xl mx-auto">
        @if(session('status'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Profile Photo Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Foto Profil</h2>
            <form id="avatar-form" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg overflow-hidden">
                            @if(optional(Auth::user())->avatar)
                                <img id="avatar-preview" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span id="avatar-initial">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                <img id="avatar-preview" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                    </div>
                    <div>
                        <input id="avatar-input" name="avatar" type="file" accept="image/*" class="hidden">
                        <button type="button" id="avatar-button" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Unggah Foto Baru
                        </button>
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG max 2MB</p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 mb-6">
            <button id="edit-btn" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                Edit Profil
            </button>
            <button id="cancel-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </button>
            <button type="submit" form="profile-form" id="save-btn" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Perubahan
            </button>
        </div>

        <!-- Profile Form -->
        <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Informasi Profil -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Profil
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                   class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-gray-50 text-gray-600"
                                   disabled>
                        </div>
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                   class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-gray-50 text-gray-600"
                                   disabled>
                        </div>
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <input type="tel" name="phone" value="{{ old('phone', optional(Auth::user())->phone) }}"
                                   placeholder="Masukkan nomor telepon"
                                   class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-gray-50 text-gray-600"
                                   disabled>
                        </div>
                        @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <input type="date" name="birthdate" value="{{ old('birthdate', optional(Auth::user())->birthdate) }}"
                                   class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-gray-50 text-gray-600"
                                   disabled>
                        </div>
                        @error('birthdate')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap"
                                      class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-gray-50 text-gray-600"
                                      disabled>{{ old('address', optional(Auth::user())->address) }}</textarea>
                        </div>
                        @error('address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Akademik -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Informasi Akademik
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', optional(Auth::user()->mahasiswa)->nim) }}"
                               placeholder="Masukkan NIM"
                               class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-gray-50 text-gray-600"
                               disabled>
                        @error('nim')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                        <input type="text" value="{{ optional(Auth::user()->mahasiswa)->program_studi }}"
                               placeholder="Program Studi"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                               disabled readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                        <input type="text" value="{{ optional(Auth::user()->mahasiswa)->angkatan }}"
                               placeholder="Angkatan"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                               disabled readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center gap-2 px-4 py-3 bg-yellow-50 border border-yellow-300 rounded-lg">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                            <span class="font-medium text-yellow-700">{{ optional(Auth::user()->mahasiswa)->status ?? 'Aktif' }}</span>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas Akhir</label>
                        <textarea rows="3" placeholder="Judul tugas akhir"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                  disabled readonly>{{ optional(Auth::user()->mahasiswa)->judul_tugas }}</textarea>
                    </div>
                </div>
            </div>
        </form>

        <!-- Ganti Password -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Ganti Password
            </h2>
            <p class="text-sm text-gray-600 mb-6">Perbarui password Anda untuk keamanan akun yang lebih baik</p>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                        <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        @error('current_password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        @error('new_password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-lg transition-colors font-medium shadow-md">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Logout Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
             <h2 class="text-xl font-bold text-gray-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </h2>
            <p class="text-sm text-gray-600 mb-6">Anda akan keluar dari sesi Anda saat ini.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg transition-colors font-medium shadow-md">
                    Logout
                </button>
            </form>
        </div>
    </div>
<script>
    // Avatar upload logic
    document.addEventListener('DOMContentLoaded', function () {
        const avatarButton = document.getElementById('avatar-button');
        const avatarInput = document.getElementById('avatar-input');

        if(avatarButton && avatarInput) {
            avatarButton.addEventListener('click', () => avatarInput.click());
            avatarInput.addEventListener('change', () => {
                if (avatarInput.files.length > 0) {
                    document.getElementById('avatar-form').submit();
                }
            });
        }
    });
</script>
@endsection

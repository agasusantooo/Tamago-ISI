<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akun - Tamago ISI</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header -->
            @include('mahasiswa.partials.header-mahasiswa')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">

                    @if(session('status'))
                        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 flex items-center">
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
                                    <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg overflow-hidden">
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
                                    <button type="button" id="avatar-button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
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
                        <button id="edit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md">
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
                        <button type="submit" form="profile-form" id="save-btn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md hidden">
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
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                               class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-600" 
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
                                               class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-600" 
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
                                               class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-600" 
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
                                               class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus;border-transparent bg-gray-50 text-gray-600" 
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
                                                  class="profile-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus;border-transparent bg-gray-50 text-gray-600" 
                                                  disabled>{{ old('address', optional(Auth::user())->address) }}</textarea>
                                    </div>
                                    @error('address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Akademik -->
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Informasi Akademik
                            </h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                                    <input type="text" name="nim" value="{{ old('nim', optional(Auth::user()->mahasiswa)->nim) }}" 
                                           placeholder="Masukkan NIM"
                                           class="profile-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus;border-transparent bg-gray-50 text-gray-600" 
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
                                    <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-300 rounded-lg">
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="font-medium text-green-700">{{ optional(Auth::user()->mahasiswa)->status ?? 'Aktif' }}</span>
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
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Ganti Password
                        </h2>
                        <p class="text-sm text-gray-600 mb-6">Perbarui password Anda untuk keamanan akun yang lebih baik</p>
                        
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Copy hidden fields untuk data yang tidak diubah -->
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                            
                            <div class="space-y-4">
                                <!-- Password Lama -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                                    <div class="relative">
                                        <input type="password" id="old-password" name="current_password"
                                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus;border-transparent" 
                                               placeholder="Masukkan password lama">
                                        <button type="button" onclick="togglePassword('old-password', 'old-eye')" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="old-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('current_password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <!-- Password Baru -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <div class="relative">
                                        <input type="password" id="new-password" name="new_password"
                                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus;border-transparent" 
                                               placeholder="Masukkan password baru"
                                               oninput="checkPasswordStrength()">
                                        <button type="button" onclick="togglePassword('new-password', 'new-eye')" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="new-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('new_password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror

                                    <!-- Password Strength Indicator -->
                                    <div id="password-strength" class="mt-3 p-3 bg-gray-50 rounded-lg hidden">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex gap-1 flex-1 mr-3">
                                                <div id="strength-1" class="h-2 flex-1 rounded-full bg-gray-200"></div>
                                                <div id="strength-2" class="h-2 flex-1 rounded-full bg-gray-200"></div>
                                                <div id="strength-3" class="h-2 flex-1 rounded-full bg-gray-200"></div>
                                                <div id="strength-4" class="h-2 flex-1 rounded-full bg-gray-200"></div>
                                            </div>
                                            <span id="strength-text" class="text-sm font-medium text-red-500"></span>
                                        </div>

                                        <div class="space-y-2 text-sm">
                                            <div id="check-length" class="flex items-center gap-2 text-gray-400">
                                                <div class="w-4 h-4 rounded-full flex items-center justify-center bg-gray-100">
                                                    <svg class="w-3 h-3 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span>Minimal 8 karakter</span>
                                            </div>
                                            <div id="check-case" class="flex items-center gap-2 text-gray-400">
                                                <div class="w-4 h-4 rounded-full flex items-center justify-center bg-gray-100">
                                                    <svg class="w-3 h-3 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span>Mengandung huruf besar dan kecil</span>
                                            </div>
                                            <div id="check-number" class="flex items-center gap-2 text-gray-400">
                                                <div class="w-4 h-4 rounded-full flex items-center justify-center bg-gray-100">
                                                    <svg class="w-3 h-3 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span>Mengandung angka</span>
                                            </div>
                                            <div id="check-special" class="flex items-center gap-2 text-gray-400">
                                                <div class="w-4 h-4 rounded-full flex items-center justify-center bg-gray-100">
                                                    <svg class="w-3 h-3 hidden check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span>Mengandung simbol khusus</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Konfirmasi Password Baru -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                    <div class="relative">
                                        <input type="password" id="confirm-password" name="new_password_confirmation"
                                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus;border-transparent" 
                                               placeholder="Konfirmasi password baru"
                                               oninput="checkPasswordMatch()">
                                        <button type="button" onclick="togglePassword('confirm-password', 'confirm-eye')" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="confirm-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="password-match-error" class="mt-2 text-sm text-red-600 hidden">Password tidak cocok</p>
                                </div>

                                <!-- Tombol Ganti Password -->
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-medium shadow-md">
                                    Ganti Password
                                </button>
    <title>Akun Saya - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f3f4f6 inset !important;
            box-shadow: 0 0 0 30px #f3f4f6 inset !important;
        }
    </style>
</head>
<body class="bg-yellow-50">

    <div class="flex h-screen overflow-hidden">
        @include('mahasiswa.partials.sidebar-mahasiswa')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('mahasiswa.partials.header-mahasiswa')

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Akun Saya</h2>

                    <!-- Profile Update Form -->
                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <form action="#" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Profile Picture -->
                                <div class="md:col-span-1 flex flex-col items-center">
                                    <img src="{{ asset('images/user.png') }}" alt="Avatar" class="w-32 h-32 rounded-full mb-4">
                                    <button type="button" class="text-sm text-yellow-600 hover:underline">Ganti Foto</button>
                                </div>

                                <!-- Form Fields -->
                                <div class="md:col-span-2 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                                        <input type="text" name="nim" id="nim" value="2021110001" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm p-2">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-8">

                            <!-- Password Update -->
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                            <div class="space-y-4">

                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <input type="password" name="new_password" id="new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm p-2" autocomplete="new-password">
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm p-2" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        // Avatar upload
        document.addEventListener('DOMContentLoaded', function () {
            const avatarButton = document.getElementById('avatar-button');
            const avatarInput = document.getElementById('avatar-input');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarInitial = document.getElementById('avatar-initial');

            if (avatarButton && avatarInput) {
                avatarButton.addEventListener('click', () => avatarInput.click());

                avatarInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (avatarPreview) {
                            avatarPreview.src = e.target.result;
                            avatarPreview.classList.remove('hidden');
                        }
                        if (avatarInitial) {
                            avatarInitial.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);

                    document.getElementById('avatar-form').submit();
                });
            }
        });

        // Edit mode toggle
        const editBtn = document.getElementById('edit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const saveBtn = document.getElementById('save-btn');
        const profileInputs = document.querySelectorAll('.profile-input');

        if (editBtn) {
            editBtn.addEventListener('click', () => {
                profileInputs.forEach(input => {
                    input.disabled = false;
                    input.classList.remove('bg-gray-50', 'text-gray-600');
                    input.classList.add('bg-white');
                });
                editBtn.classList.add('hidden');
                cancelBtn.classList.remove('hidden');
                saveBtn.classList.remove('hidden');
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                profileInputs.forEach(input => {
                    input.disabled = true;
                    input.classList.add('bg-gray-50', 'text-gray-600');
                    input.classList.remove('bg-white');
                });
                const form = document.getElementById('profile-form');
                if (form) form.reset();
                cancelBtn.classList.add('hidden');
                saveBtn.classList.add('hidden');
                editBtn.classList.remove('hidden');
            });
        }

        // Toggle password visibility
        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            
            if (!input) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                if (eye) eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21'></path>';
            } else {
                input.type = 'password';
                if (eye) eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // Password strength checker
        function checkPasswordStrength() {
            const el = document.getElementById('new-password');
            if (!el) return;
            const password = el.value;
            const strengthContainer = document.getElementById('password-strength');
            
            if (password.length === 0) {
                if (strengthContainer) strengthContainer.classList.add('hidden');
                return;
            }
            
            if (strengthContainer) strengthContainer.classList.remove('hidden');
            
            const checks = {
                length: password.length >= 8,
                case: /[a-z]/.test(password) && /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            const strength = Object.values(checks).filter(Boolean).length;
            
            // Update strength bars
            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById(`strength-${i}`);
                if (!bar) continue;
                if (i <= strength) {
                    bar.classList.remove('bg-gray-200');
                    if (strength <= 2) bar.classList.add('bg-red-500');
                    else if (strength === 3) bar.classList.add('bg-yellow-500');
                    else bar.classList.add('bg-green-500');
                } else {
                    bar.className = 'h-2 flex-1 rounded-full bg-gray-200';
                }
            }
            
            // Update strength text
            const strengthText = document.getElementById('strength-text');
            if (strengthText) {
                if (strength <= 2) {
                    strengthText.textContent = 'Lemah';
                    strengthText.className = 'text-sm font-medium text-red-500';
                } else if (strength === 3) {
                    strengthText.textContent = 'Sedang';
                    strengthText.className = 'text-sm font-medium text-yellow-500';
                } else {
                    strengthText.textContent = 'Kuat';
                    strengthText.className = 'text-sm font-medium text-green-500';
                }
            }
            
            // Update check marks
            updateCheck('check-length', checks.length);
            updateCheck('check-case', checks.case);
            updateCheck('check-number', checks.number);
            updateCheck('check-special', checks.special);
        }

        function updateCheck(elementId, passed) {
            const element = document.getElementById(elementId);
            if (!element) return;
            const icon = element.querySelector('.check-icon');
            const circle = element.querySelector('.w-4');
            
            if (passed) {
                element.classList.remove('text-gray-400');
                element.classList.add('text-green-600');
                if (circle) {
                    circle.classList.remove('bg-gray-100');
                    circle.classList.add('bg-green-100');
                }
                if (icon) icon.classList.remove('hidden');
            } else {
                element.classList.add('text-gray-400');
                element.classList.remove('text-green-600');
                if (circle) {
                    circle.classList.add('bg-gray-100');
                    circle.classList.remove('bg-green-100');
                }
                if (icon) icon.classList.add('hidden');
            }
        }

        // Check password match
        function checkPasswordMatch() {
            const newPasswordEl = document.getElementById('new-password');
            const confirmEl = document.getElementById('confirm-password');
            const errorElement = document.getElementById('password-match-error');
            if (!newPasswordEl || !confirmEl || !errorElement) return;
            const newPassword = newPasswordEl.value;
            const confirmPassword = confirmEl.value;
            
            if (confirmPassword.length > 0 && newPassword !== confirmPassword) {
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Akun Saya - Tamago ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-purple-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        @include('dosen_penguji.partials.sidebar-dosen_penguji')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('dosen_penguji.partials.header-dosen_penguji')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-purple-900 mb-6">Akun Saya</h2>

                    <div class="bg-white rounded-lg shadow-sm p-8">
                        <form id="profileForm" action="#" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Profile Picture -->
                                <div class="md:col-span-1 flex flex-col items-center">
                                    <div class="w-32 h-32 rounded-full bg-purple-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-user text-4xl text-purple-600"></i>
                                    </div>
                                    <!-- <button type="button" class="text-sm text-purple-600 hover:underline">Ganti Foto</button> -->
                                </div>

                                <!-- Form Fields -->
                                <div class="md:col-span-2 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                                    </div>
                                    @if(Auth::user()->dosen)
                                    <div>
                                        <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN</label>
                                        <input type="text" name="nidn" id="nidn" value="{{ Auth::user()->dosen->nidn ?? '-' }}" disabled class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 shadow-sm sm:text-sm">
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-8">

                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm" autocomplete="new-password" placeholder="Kosongkan jika tidak ingin mengubah">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm" autocomplete="new-password" placeholder="Konfirmasi password baru">
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">Simpan Perubahan</button>
                            </div>
                        </form>
@extends('dosen_penguji.layouts.app')

@section('title', 'Akun - Tamago ISI')

@section('content')
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

                    <!-- Info Box -->
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <p class="text-sm text-blue-900">
                            <i class="fas fa-info-circle mr-2"></i>
                            Jika Anda ingin mengubah informasi lain, silakan hubungi administrator sistem.
                        </p>
                    </div>
                </div>
            </form>
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
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                 <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan Profil
                    </button>
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
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-medium shadow-md">
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
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Fitur update profile akan diimplementasikan di fase berikutnya. Untuk sekarang, perubahan hanya disimpan di session.');
        });
    </script>
</body>
</html>

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

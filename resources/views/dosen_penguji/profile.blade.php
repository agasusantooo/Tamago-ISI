<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-purple-50 text-gray-800">
    @php
        $mahasiswaAktifCount = 10; 
        $tugasReview = 5; 
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dosen_penguji.partials.sidebar-dosen_penguji')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dosen_penguji.partials.header-dosen_penguji')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Akun Saya</h2>

                    <div class="bg-white rounded-xl shadow-sm p-8">
                        <form action="#" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Profile Picture -->
                                <div class="md:col-span-1 flex flex-col items-center">
                                    <img src="{{ asset('images/user.png') }}" alt="Avatar" class="w-32 h-32 rounded-full mb-4">
                                    <button type="button" class="text-sm text-purple-600 hover:underline">Ganti Foto</button>
                                </div>

                                <!-- Form Fields -->
                                <div class="md:col-span-2 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-8">

                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2" autocomplete="new-password">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

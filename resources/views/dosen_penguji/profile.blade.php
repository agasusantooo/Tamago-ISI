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
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <p class="text-sm text-blue-900">
                            <i class="fas fa-info-circle mr-2"></i>
                            Jika Anda ingin mengubah informasi lain, silakan hubungi administrator sistem.
                        </p>
                    </div>
                </div>
            </main>
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
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Akun Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Akun Saya</h2>

                <!-- Profile Update Form -->
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @if(session('status'))
                        <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900 p-4 text-green-800 dark:text-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Profile Picture -->
                        <div class="md:col-span-1 flex flex-col items-center">
                            <img src="{{ asset('images/user.png') }}" alt="Avatar" class="w-32 h-32 rounded-full mb-4">
                            <button type="button" class="text-sm text-indigo-600 hover:underline">Ganti Foto</button>
                        </div>

                        <!-- Form Fields -->
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm p-2">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm p-2">
                            </div>
                            <div>
                                <label for="nim" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIM</label>
                                <input type="text" name="nim" id="nim" value="{{ optional(Auth::user()->mahasiswa)->nim ?? '' }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 shadow-sm sm:text-sm p-2">
                            </div>
                        </div>
                    </div>

                    <hr class="my-8">

                    <!-- Password Update -->
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ubah Password</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm p-2" autocomplete="current-password">
                            @error('current_password')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm p-2" autocomplete="new-password">
                            @error('new_password')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 sm:text-sm p-2" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

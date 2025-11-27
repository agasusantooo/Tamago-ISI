<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS (tanpa Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>

        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    {{-- Navbar sederhana --}}
    <nav class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                {{ config('app.name', 'Dashboard') }}
            </h1>
            <div class="space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-500">Dashboard</a>
                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-500">Bimbingan</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 dark:text-gray-300 hover:text-red-500">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Header (opsional, untuk halaman yang punya slot header) --}}
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
    @livewire('header-progress')

    {{-- Konten utama --}}
    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Koordinator') - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    @stack('styles')
</head>
<body class="bg-green-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('koordinator_tefa.partials.sidebar-koordinator')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- The header can be shared for now, or customized later --}}
            @include('koordinator_ta.partials.header-koordinator')

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

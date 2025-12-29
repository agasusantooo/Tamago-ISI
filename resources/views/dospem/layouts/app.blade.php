<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Dosen Pembimbing - Tamago ISI')</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-blue-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        @include('dospem.partials.sidebar-dospem')

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('dospem.partials.header-dospem')

            <main class="flex-1 overflow-y-auto">
                <div>
                    @yield('content')
                </div>
            </main>
            @yield('scripts')
        </div>
    </div>
</body>
</html>

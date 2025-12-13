<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Mahasiswa')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('mahasiswa.partials.sidebar-mahasiswa')

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Header --}}
                @php
                    $currentPageTitle = null;
                    if (View::hasSection('page-title')) {
                        $currentPageTitle = View::yieldContent('page-title');
                    }

                    // Special override for Tefa Fair pages due to persistent issues
                    if (Request::routeIs('mahasiswa.tefa-fair.*')) { // covers both index and create
                        $currentPageTitle = 'Progress Tugas Akhir';
                    }
                @endphp
                @include('mahasiswa.partials.header-mahasiswa', ['pageTitle' => $currentPageTitle ?? 'Progress Tugas Akhir'])

            {{-- Konten halaman --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="max-w-7xl mx-auto mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-yellow-800 font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto mb-4 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="max-w-7xl mx-auto mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <ul class="list-disc list-inside text-sm text-yellow-800">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        if (sidebarToggle) {
            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.toggle("hidden");
            });
        }
    </script>
</body>
</html>
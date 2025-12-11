<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator TEFA - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        @include('koordinator_tefa.partials.sidebar-koordinator')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- Note: header-koordinator mungkin perlu dibuat juga jika isinya berbeda --}}
            @include('koordinator_ta.partials.header-koordinator')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Dashboard Koordinator TEFA</h3>
                        <p>Selamat datang di dashboard Koordinator TEFA Fair. Silakan gunakan menu di samping untuk mengelola kegiatan TEFA Fair.</p>
                    </div>

                    {{-- Konten spesifik untuk Koordinator TEFA dapat ditambahkan di sini --}}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
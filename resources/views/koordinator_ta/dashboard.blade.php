<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koordinator - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-50 text-gray-800">
    @php
        // Dummy data untuk menghindari error di header
        $mahasiswaAktifCount = 150; 
        $tugasReview = 25; 
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('koordinator_ta.partials.sidebar-koordinator')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('koordinator_ta.partials.header-koordinator')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Dashboard Koordinator TA</h3>
                        <p>Selamat datang di dashboard Koordinator Tugas Akhir. Silakan gunakan menu di samping untuk mengelola jadwal, memonitor mahasiswa, dan mengatur mata kuliah.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
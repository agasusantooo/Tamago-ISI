<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen Penguji - Tamago ISI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-purple-50 text-gray-800">
    @php
        // Dummy data untuk menghindari error di header
        $mahasiswaAktifCount = 10; // Jumlah mahasiswa yang akan diuji
        $tugasReview = 5; // Jumlah revisi yang perlu diperiksa
    @endphp
    <div class="flex h-screen overflow-hidden">

        @include('dosen_penguji.partials.sidebar-dosen_penguji')

        <div class="flex-1 flex flex-col overflow-hidden">
            
            @include('dosen_penguji.partials.header-dosen_penguji')

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-purple-800 mb-4">Dashboard Dosen Penguji</h3>
                        <p>Selamat datang di dashboard Dosen Penguji. Silakan gunakan menu di samping untuk mengakses halaman penilaian ujian.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

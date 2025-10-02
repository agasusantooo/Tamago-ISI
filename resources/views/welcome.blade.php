<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAMAGO-ISI</title>
    @vite('resources/css/app.css')
</head>
<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow p-4">
        <h1 class="text-2xl font-bold text-center text-indigo-600">TAMAGO ISI</h1>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center items-center text-center">
        <h2 class="text-3xl font-bold mb-1">Sistem Manajemen Tugas Akhir</h2>
        <p class="text-gray-600 mb-8">Kelola proposal, bimbingan, hingga ujian dengan mudah dan seru.</p>
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Register
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 text-center">
        <p>&copy; {{ date('Y') }} TAMAGO-ISI. All Rights Reserved.</p>
    </footer>

</body>
</html>

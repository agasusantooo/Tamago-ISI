<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-900">
    <!-- Halaman login/register hanya slot -->
    <div class="min-h-screen flex flex-col justify-center items-center">
        {{ $slot }}
    </div>
</body>
</html>

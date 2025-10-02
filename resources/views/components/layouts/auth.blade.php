<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-300">

    {{-- INI ADALAH LAYOUT AUTH, TIDAK ADA HEADER DI SINI --}}
    
    {{ $slot }}

</body>
</html>
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

        <!-- Theme init: apply saved theme (runs before CSS to avoid flash)
             Default to LIGHT unless user explicitly chose DARK. -->
        <script>
            (function () {
                try {
                    var theme = null;
                    try { theme = localStorage.getItem('theme'); } catch (e) { theme = null; }

                    // If the user explicitly saved 'dark', enable it. Otherwise default to light.
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        try { localStorage.setItem('theme', 'light'); } catch (e) {}
                    }

                    // Expose toggleTheme to global scope
                    window.toggleTheme = function () {
                        var html = document.documentElement;
                        var newTheme = 'light';
                        if (html.classList.contains('dark')) {
                            html.classList.remove('dark');
                            try { localStorage.setItem('theme', 'light'); } catch (e) {}
                            newTheme = 'light';
                        } else {
                            html.classList.add('dark');
                            try { localStorage.setItem('theme', 'dark'); } catch (e) {}
                            newTheme = 'dark';
                        }

                        // Persist preference server-side if route exists
                        try {
                            var themeUrl = @json(route('profile.theme'));
                            fetch(themeUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ theme: newTheme }),
                                credentials: 'same-origin'
                            }).catch(function(e){ /* ignore */ });
                        } catch (e) {
                            // route may not exist in some environments; ignore
                        }
                    };
                } catch (e) {
                    // ignore
                }
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

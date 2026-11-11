<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ auth()->user()?->venue?->name ?? config('app.name') }}</title>

    {{-- Thème appliqué avant le premier affichage : pas de flash clair/sombre --}}
    <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#f59e0b">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body x-data class="h-full bg-slate-50 font-[Inter,ui-sans-serif,system-ui] text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">

    {{-- Fond décoratif discret --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 right-0 h-96 w-96 rounded-full bg-amber-200/30 blur-3xl dark:bg-amber-500/5"></div>
        <div class="absolute bottom-0 left-64 h-80 w-80 rounded-full bg-sky-200/30 blur-3xl dark:bg-sky-500/5"></div>
    </div>

    <x-sidebar />

    <div class="flex min-h-screen flex-col md:pl-64">
        <x-topbar />

        <main class="flex-1 px-4 pb-28 pt-6 sm:px-6 md:pb-12 lg:px-8">
            <div class="mx-auto max-w-7xl">
                {{ $slot }}
            </div>
        </main>
    </div>

    <x-bottom-nav />
    <x-toast />

    {{-- Barre de progression pendant la navigation --}}
    <div id="nav-progress"
         class="pointer-events-none fixed inset-x-0 top-0 z-[70] h-0.5 origin-left scale-x-0 bg-gradient-to-r from-amber-400 to-amber-600 transition-transform duration-300"></div>

    <script>
        (function () {
            var bar = document.getElementById('nav-progress');
            document.addEventListener('livewire:navigating', function () {
                bar.style.transition = 'transform 300ms';
                bar.style.transform = 'scaleX(0.7)';
            });
            document.addEventListener('livewire:navigated', function () {
                bar.style.transform = 'scaleX(1)';
                setTimeout(function () {
                    bar.style.transition = 'none';
                    bar.style.transform = 'scaleX(0)';
                    setTimeout(function () { bar.style.transition = ''; }, 50);
                }, 200);
            });
        })();
    </script>

    @livewireScripts
</body>
</html>
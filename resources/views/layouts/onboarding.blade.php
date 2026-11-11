<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-amber-50 font-[Inter,ui-sans-serif,system-ui] text-slate-800 antialiased dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 dark:text-slate-100">
    <main class="flex min-h-screen items-center justify-center p-6">
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>
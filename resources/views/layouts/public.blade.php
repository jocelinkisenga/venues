@props(['title' => null])

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ? $title.' · ' : '' }}Explorer les salles</title>
    <meta name="description" content="Trouvez et réservez une salle d'événements en ligne.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full bg-slate-50 font-[Inter,ui-sans-serif,system-ui] text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">

    <header class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('explorer') }}" class="flex items-center gap-2.5 font-semibold tracking-tight">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 text-sm font-bold text-white shadow-sm">S</span>
                Explorer les salles
            </a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">
                Espace gestionnaire
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-slate-200/70 py-8 text-center text-xs text-slate-500 dark:border-slate-800">
        Chaque demande est confirmée par la salle. La date est bloquée temporairement pendant l'étude de votre demande.
    </footer>

    @livewireScripts
</body>
</html>
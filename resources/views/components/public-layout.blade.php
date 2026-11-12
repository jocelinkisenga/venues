@props(['title' => null, 'description' => null])

<!DOCTYPE html>
<html lang="fr" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ? $title.' · ' : '' }}Explorer les salles</title>
    <meta name="description" content="{{ $description ?? 'Trouvez et réservez une salle d\'événements en ligne.' }}">
    <meta name="theme-color" content="#f59e0b">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

<x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="flex min-h-full flex-col bg-stone-50 font-[Inter,ui-sans-serif,system-ui] text-stone-800 antialiased dark:bg-stone-950 dark:text-stone-100">

    <header class="sticky top-0 z-40 border-b border-stone-200/70 bg-white/80 backdrop-blur-xl dark:border-stone-800 dark:bg-stone-950/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('explorer') }}" class="group flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-sm font-bold text-white shadow-sm transition group-hover:shadow-md">S</span>
                <span class="text-[15px] font-semibold tracking-tight">Explorer les salles</span>
            </a>

            <a href="{{ route('login') }}"
               class="inline-flex items-center rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-sm font-medium shadow-sm transition hover:bg-stone-50 dark:border-stone-700 dark:bg-stone-900 dark:hover:bg-stone-800">
                Espace gestionnaire
            </a>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-stone-200/70 bg-white dark:border-stone-800 dark:bg-stone-950">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">
                <div class="max-w-sm">
                    <p class="font-semibold">Explorer les salles</p>
                    <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">
                        Réservez une salle d'événements en ligne. Chaque demande est confirmée par la salle.
                    </p>
                </div>
                <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white">
                    Espace gestionnaire →
                </a>
            </div>
            <p class="mt-10 border-t border-stone-200/70 pt-6 text-xs text-stone-400 dark:border-stone-800">
                © {{ date('Y') }} · Tous droits réservés
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
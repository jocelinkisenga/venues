<!DOCTYPE html>
<html lang="fr" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ auth()->user()?->venue?->name ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-amber-50 text-slate-800 antialiased dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 dark:text-slate-100 pb-24 md:pb-8">
    <header class="sticky top-0 z-30 border-b border-white/40 bg-white/60 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="font-bold tracking-tight">{{ auth()->user()->venue->name }}</a>
                <nav class="hidden items-center gap-3 text-sm md:flex">
                    <a href="{{ route('calendar') }}" class="hover:text-amber-600">Calendrier</a>
                    @if(auth()->user()->role->canSell())
                        <a href="{{ route('bookings.create') }}" class="hover:text-amber-600">Réserver</a>
                        <a href="{{ route('crm') }}" class="hover:text-amber-600">CRM</a>
                    @endif
                    @if(auth()->user()->role->value === 'proprietaire')
                        <a href="{{ route('audit') }}" class="hover:text-amber-600">Journal</a>
                    @endif
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden text-xs text-slate-500 dark:text-slate-400 sm:inline">{{ auth()->user()->role->label() }}</span>
                <button type="button"
                        @click="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light')"
                        class="rounded-full p-2 hover:bg-slate-200/70 dark:hover:bg-slate-800"
                        aria-label="Changer de thème">
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark">☀️</span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-slate-500 hover:text-red-500">Quitter</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl p-4">
        {{ $slot }}
    </main>

    <nav class="fixed inset-x-0 bottom-0 z-30 border-t border-white/40 bg-white/80 backdrop-blur-xl md:hidden dark:border-slate-800 dark:bg-slate-900/80"
         style="padding-bottom: env(safe-area-inset-bottom)">
        <div class="grid grid-cols-4 text-center text-xs font-medium">
            <a href="{{ route('dashboard') }}" class="py-3">Accueil</a>
            <a href="{{ route('calendar') }}" class="py-3">Calendrier</a>
            @if(auth()->user()->role->canSell())
                <a href="{{ route('bookings.create') }}" class="py-3">Réserver</a>
                <a href="{{ route('crm') }}" class="py-3">CRM</a>
            @endif
        </div>
    </nav>

    @livewireScripts
</body>
</html>

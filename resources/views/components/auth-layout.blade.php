@props(['title', 'subtitle' => null])

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f59e0b">
    <title>{{ $title }} · {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <x-pwa-head />
    <x-theme-script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full bg-stone-50 font-[Inter,ui-sans-serif,system-ui] text-stone-800 antialiased dark:bg-stone-950 dark:text-stone-100">
    <div class="relative flex min-h-dvh flex-col">

        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-amber-100/70 to-transparent dark:from-amber-400/5"></div>

        <header class="px-5 pt-6 sm:px-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-sm font-bold text-white shadow-sm">
                   <x-app-logo class="h-9 w-9" />
                </span>
                <span class="text-[15px] font-semibold tracking-tight">{{ config('app.name') }}</span>
            </a>
        </header>

        <main class="flex flex-1 items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">

                <div class="mb-8">
                    <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="mt-2 text-sm leading-relaxed text-stone-500 dark:text-stone-400">{{ $subtitle }}</p>
                    @endif
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-900/5 sm:p-8 dark:bg-stone-900 dark:ring-white/10">
                    {{ $slot }}
                </div>

                <p class="mt-8 text-center text-xs text-stone-400">
                    © {{ date('Y') }} {{ config('app.name') }}
                </p>
            </div>
        </main>
    </div>
</body>
</html>
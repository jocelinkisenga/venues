<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="$store.theme.theme === 'dark' ? 'dark' : ''">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <title>{{ config('app.name', 'unstuck my taxes') }}</title>
        <meta name="description" content="Optimize your tax profile with AI-powered insights. Specialized for gig workers, freelancers, and expats.">
        <meta name="robots" content="index, follow">

        {{-- open grah --}}
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="ILANDS SOLUTIONS | Smart AI Tax Report">
        <meta property="og:description" content="Discover missed deductions and plan your tax year with our advanced AI analysis.">
        <meta property="og:image" content="{{ asset('logo.png') }}">
        <meta property="og:site_name" content="ILANDS SOLUTIONS">

        {{-- twitter --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="ILANDS SOLUTIONS | AI Tax Strategies">
        <meta name="twitter:description" content="Get your comprehensive tax profile and AI-powered report in 10 minutes.">
        <meta name="twitter:image" content="{{ asset('logo.png') }}">

        {{-- mobile --}}
        <meta name="theme-color" content="#2563eb" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
        @if(request()->routeIs('dashboard*'))
        <meta name="robots" content="noindex, nofollow">
       @endif

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
                <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/gh/snd-lib/snd-lib@v1.2.4/dist/browser/snd.js?kit=01"></script>
        <link rel="stylesheet" href="{{asset('assets/css/main.css')}}"/>
        @stack('styles')
        @livewireStyles
    </head>
    <body  class=" bg-white text-gray-900 dark:bg-black dark:text-white transition-colors duration-300" >
      @include('components.navbar')
               {{ $slot }}
{{--     <button
  id="installBtn"
  class="hidden fixed bottom-6 right-6 z-50 
         flex items-center gap-2 
         bg-white text-gray-900 
         border border-gray-200 
         px-5 py-3 rounded-2xl 
         shadow-xl hover:shadow-2xl 
         transition-all duration-300 
         hover:scale-105 active:scale-95"
>
  <span class="text-xl">📲</span>

  <div class="flex flex-col text-left">
    <span class="text-sm font-semibold">
      Installer Ilands
    </span>
    <span class="text-xs text-gray-500">
      Accès plus rapide & hors ligne
    </span>
  </div>
</button> --}}
@livewire("bot.chat")

    @include('components.footer')
   
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{asset('assets/js/main.js')}}" type="script"></script>


<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> 

{{-- google --}}
<script>
    lucide.createIcons();
</script>
 @livewireScripts
 @stack('scripts')
    </body>
</html>

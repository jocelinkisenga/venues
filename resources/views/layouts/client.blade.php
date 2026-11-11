<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="$store.theme.theme === 'dark' ? 'dark' : ''">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Unstuck my taxes</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 @livewireStyles
</head>
<body class="font-sans antialiased min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-300 pb-[env(safe-area-inset-bottom,0px)] [-webkit-tap-highlight-color:transparent]">

<!-- SVG icon sprite -->
<svg xmlns="http://www.w3.org/2000/svg" class="hidden" aria-hidden="true">
  <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></symbol>
  <symbol id="i-library" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4v16M9 4v16M14 6l5 14M14 6l3-1"/></symbol>
  <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></symbol>
  <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
  <symbol id="i-crown" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8l5 4 5-8 5 8 5-4-2 11H4z"/></symbol>
  <symbol id="i-settings" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></symbol>
  <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
  <symbol id="i-play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M10 8l6 4-6 4z"/></symbol>
  <symbol id="i-file" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8M8 9h2"/></symbol>
  <symbol id="i-sparkles" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9zM19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z"/></symbol>
  <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2zM22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"/></symbol>
  <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
  <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></symbol>
  <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
  <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0zM12 9v4M12 17h.01"/></symbol>
</svg>

<!-- Disclaimer bar -->
<div class="lg:pl-64 border-b bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/50 dark:border-yellow-800 dark:text-yellow-300 pt-[env(safe-area-inset-top,0px)]">
  <p class="flex items-center justify-center gap-2 px-4 py-2 text-xs sm:text-sm text-center">
    <svg class="h-4 w-4 shrink-0"><use href="#i-alert"/></svg>
    <span><strong class="font-semibold">Educational guidance only.</strong> Not tax preparation or legal advice.</span>
  </p>
</div>

<!-- Scrim -->
<div id="scrim" class="fixed inset-0 z-40 bg-slate-900/50 dark:bg-black/60 opacity-0 pointer-events-none transition-opacity duration-300 ease-in lg:hidden" aria-hidden="true"></div>

<!-- Sidebar -->
<x-aside/>

<div class="lg:pl-64">
  <header class="sticky top-0 z-30 border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur lg:hidden pt-[env(safe-area-inset-top,0px)]">
    <div class="flex h-14 items-center justify-between px-4">
      <a href="#" class="flex items-center gap-2
      .5 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2">
        <span class="grid h-8 w-8 place-items-center rounded-lg bg-blue-900 dark:bg-blue-700 text-sm font-bold text-white">U</span>
        <span class="text-sm font-bold tracking-wide">TAXES</span>
      </a>
      <button id="openNav" type="button" class="grid h-10 w-10 place-items-center rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-950 focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2" aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
        <svg class="h-6 w-6"><use href="#i-menu"/></svg>
      </button>
    </div>
  </header>
<div class="dark:bg-slate-900">
{{$slot}}
</div>

<!-- Floating chat button -->
{{-- <button type="button" class="fixed right-4 z-30 grid h-14 w-14 place-items-center rounded-full bg-blue-900 dark:bg-blue-700 text-white shadow-lg transition active:scale-95 sm:right-6 bottom-[calc(1rem+env(safe-area-inset-bottom,0px))] focus-visible:outline-2 focus-visible:outline-blue-900 dark:focus-visible:outline-blue-700 focus-visible:outline-offset-2" aria-label="Open AI chat">
  <svg class="h-6 w-6"><use href="#i-chat"/></svg>
</button> --}}

<script>
(function () {
  var sidebar = document.getElementById('sidebar');
  var scrim = document.getElementById('scrim');
  var openBtn = document.getElementById('openNav');
  var closeBtn = document.getElementById('closeNav');
  var desktop = window.matchMedia('(min-width: 1024px)');

  function open() {
    sidebar.classList.remove('-translate-x-full', 'invisible');
    sidebar.classList.add('translate-x-0', '!visible');
    scrim.classList.remove('opacity-0', 'pointer-events-none');
    scrim.classList.add('opacity-100', 'pointer-events-auto');
    document.body.classList.add('overflow-hidden');
    openBtn.setAttribute('aria-expanded', 'true');
    closeBtn.focus({ preventScroll: true });
  }
  
  function close(returnFocus) {
    sidebar.classList.remove('translate-x-0', '!visible');
    sidebar.classList.add('-translate-x-full', 'invisible');
    scrim.classList.remove('opacity-100', 'pointer-events-auto');
    scrim.classList.add('opacity-0', 'pointer-events-none');
    document.body.classList.remove('overflow-hidden');
    openBtn.setAttribute('aria-expanded', 'false');
    if (returnFocus) openBtn.focus({ preventScroll: true });
  }

  openBtn.addEventListener('click', open);
  closeBtn.addEventListener('click', function () { close(true); });
  scrim.addEventListener('click', function () { close(true); });
  
  sidebar.querySelectorAll('nav a').forEach(function (a) {
    a.addEventListener('click', function () { if (!desktop.matches) close(false); });
  });
  
  document.addEventListener('keydown', function (e) {
    // Vérifier si la classe de bascule actuelle est présente (équivalent à .is-open)
    var isOpen = sidebar.classList.contains('translate-x-0');
    
    if (e.key === 'Escape' && isOpen) close(true);
    
    // Keep Tab inside the drawer while it is open on mobile
    if (e.key === 'Tab' && isOpen && !desktop.matches) {
      var f = sidebar.querySelectorAll('a[href], button');
      var first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });
  
  desktop.addEventListener('change', function (e) { if (e.matches) close(false); });
})();
</script>
    <script src="{{asset('assets/js/main.js')}}" type="script"></script>
 @livewireScripts
</body>
</html>
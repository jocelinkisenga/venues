<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>ILANDS – Dashboard template</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 @livewireStyles
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
        colors: {
          page: 'var(--page)',
          surface: 'var(--surface)',
          ink: 'var(--ink)',
          muted: 'var(--muted)',
          line: 'var(--line)',
          navy: 'var(--navy)',
          'navy-soft': 'var(--navy-soft)',
        },
      },
    },
  };
</script>
<style>
  :root {
    --page: #f8fafc; --surface: #ffffff; --ink: #1e293b; --muted: #64748b; --line: #e2e8f0;
    --navy: #1e3a5f; --navy-soft: #eaf0f8;
    --warn-bg: #fefce8; --warn-line: #fde68a; --warn-ink: #92400e;
    --overlay: rgba(15, 23, 42, .5);
  }
  @media (prefers-color-scheme: dark) {
    :root:not([data-theme="light"]) {
      --page: #0b1220; --surface: #131c2e; --ink: #f1f5f9; --muted: #94a3b8; --line: #263349;
      --navy: #35588a; --navy-soft: #1c2b44;
      --warn-bg: #2a2410; --warn-line: #4d4216; --warn-ink: #fcd34d;
      --overlay: rgba(0, 0, 0, .6);
    }
  }
  :root[data-theme="dark"] {
    --page: #0b1220; --surface: #131c2e; --ink: #f1f5f9; --muted: #94a3b8; --line: #263349;
    --navy: #35588a; --navy-soft: #1c2b44;
    --warn-bg: #2a2410; --warn-line: #4d4216; --warn-ink: #fcd34d;
    --overlay: rgba(0, 0, 0, .6);
  }
  :root { box-sizing: border-box; padding-bottom: env(safe-area-inset-bottom, 0px); }
  html { scroll-padding-top: env(safe-area-inset-top, 0px); }
  body { background: var(--page); color: var(--ink); -webkit-tap-highlight-color: transparent; }
  :focus-visible { outline: 2px solid var(--navy); outline-offset: 2px; border-radius: 8px; }

  /* Sidebar: off-canvas on mobile, fixed on desktop */
  .drawer {
    transform: translateX(-100%);
    visibility: hidden;
    transition: transform .3s cubic-bezier(.32,.72,0,1), visibility 0s linear .3s;
  }
  .drawer.is-open { transform: translateX(0); visibility: visible; transition-delay: 0s; }
  .scrim { opacity: 0; pointer-events: none; transition: opacity .3s ease; background: var(--overlay); }
  .scrim.is-open { opacity: 1; pointer-events: auto; }
  @media (min-width: 1024px) {
    .drawer { transform: none; visibility: visible; transition: none; }
    .scrim { display: none; }
  }
  @media (prefers-reduced-motion: reduce) {
    .drawer, .scrim { transition-duration: .01ms !important; }
  }
  body.no-scroll { overflow: hidden; }
</style>
</head>
<body class="font-sans antialiased min-h-screen">

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
<div class="lg:pl-64 border-b" style="background:var(--warn-bg);border-color:var(--warn-line);color:var(--warn-ink);padding-top:env(safe-area-inset-top,0px)">
  <p class="flex items-center justify-center gap-2 px-4 py-2 text-xs sm:text-sm text-center">
    <svg class="h-4 w-4 shrink-0"><use href="#i-alert"/></svg>
    <span><strong class="font-semibold">Educational guidance only.</strong> Not tax preparation or legal advice.</span>
  </p>
</div>

<!-- Scrim -->
<div id="scrim" class="scrim fixed inset-0 z-40" aria-hidden="true"></div>

<!-- Sidebar -->
<aside id="sidebar" class="drawer fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col border-r border-line bg-surface lg:w-64"
       aria-label="Main navigation" style="padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)">
  <div class="flex h-14 items-center justify-between px-4">
    <a href="#" class="flex items-center gap-2.5 rounded-lg">
      <span class="grid h-8 w-8 place-items-center rounded-lg bg-navy text-sm font-bold text-white">I</span>
      <span class="text-sm font-bold tracking-wide">ILANDS</span>
    </a>
    <button id="closeNav" type="button" class="grid h-9 w-9 place-items-center rounded-lg text-muted hover:bg-page lg:hidden" aria-label="Close menu">
      <svg class="h-5 w-5"><use href="#i-x"/></svg>
    </button>
  </div>

  <nav class="mt-2 flex-1 overflow-y-auto px-3" aria-label="Primary">
    <ul class="space-y-1">
      <li><a href="#" aria-current="page" class="flex items-center gap-3 rounded-xl bg-navy px-3 py-2.5 text-sm font-medium text-white"><svg class="h-[18px] w-[18px]"><use href="#i-grid"/></svg>Dashboard</a></li>
      <li><a href="{{route('library.index')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>Library</a></li>
      <li><a href="{{route('documents')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My documents</a></li>
      <li><a href="{{route('chat')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-chat"/></svg>Tax chat</a></li>
       <li><a href="{{route('hystory')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My hystory chats</a></li>
              <li><a href="{{route('reports')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-library"/></svg>My generated reports</a></li>

      <li><a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-users"/></svg>Priority match</a></li>
    </ul>
  </nav>

  <div class="border-t border-line px-3 py-3">
    <ul class="space-y-1">
      <li><a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-crown"/></svg>Upgrade</a></li>
      <li><a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink"><svg class="h-[18px] w-[18px]"><use href="#i-settings"/></svg>Settings</a></li>
    </ul>
    <p class="mt-2 truncate px-3 text-xs text-muted" title="you@example.com">you@example.com</p>
    <button type="button" class="mt-1 flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-page hover:text-ink">
      <svg class="h-[18px] w-[18px]"><use href="#i-logout"/></svg>Log out
    </button>
  </div>
</aside>
<div class="lg:pl-64">
  <header class="sticky top-0 z-30 border-b border-line bg-surface/90 backdrop-blur lg:hidden" style="padding-top:env(safe-area-inset-top,0px)">
    <div class="flex h-14 items-center justify-between px-4">
      <a href="#" class="flex items-center gap-2.5">
        <span class="grid h-8 w-8 place-items-center rounded-lg bg-navy text-sm font-bold text-white">I</span>
        <span class="text-sm font-bold tracking-wide">ILANDS</span>
      </a>
      <button id="openNav" type="button" class="grid h-10 w-10 place-items-center rounded-lg text-muted hover:bg-page" aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
        <svg class="h-6 w-6"><use href="#i-menu"/></svg>
      </button>
    </div>
  </header>

{{$slot}}
</div>

<!-- Floating chat button -->
<button type="button" class="fixed right-4 z-30 grid h-14 w-14 place-items-center rounded-full bg-navy text-white shadow-lg transition active:scale-95 sm:right-6"
        style="bottom:calc(1rem + env(safe-area-inset-bottom,0px))" aria-label="Open AI chat">
  <svg class="h-6 w-6"><use href="#i-chat"/></svg>
</button>

<script>
(function () {
  var sidebar = document.getElementById('sidebar');
  var scrim = document.getElementById('scrim');
  var openBtn = document.getElementById('openNav');
  var closeBtn = document.getElementById('closeNav');
  var desktop = window.matchMedia('(min-width: 1024px)');

  function open() {
    sidebar.classList.add('is-open');
    scrim.classList.add('is-open');
    document.body.classList.add('no-scroll');
    openBtn.setAttribute('aria-expanded', 'true');
    closeBtn.focus({ preventScroll: true });
  }
  function close(returnFocus) {
    sidebar.classList.remove('is-open');
    scrim.classList.remove('is-open');
    document.body.classList.remove('no-scroll');
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
    if (e.key === 'Escape' && sidebar.classList.contains('is-open')) close(true);
    // Keep Tab inside the drawer while it is open on mobile
    if (e.key === 'Tab' && sidebar.classList.contains('is-open') && !desktop.matches) {
      var f = sidebar.querySelectorAll('a[href], button');
      var first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });
  desktop.addEventListener('change', function (e) { if (e.matches) close(false); });
})();
</script>
 @livewireScripts
</body>
</html>

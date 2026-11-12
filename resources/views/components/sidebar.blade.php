@php
    $user = auth()->user();
    $venue = $user->venue;
    $sections = \App\Support\Navigation::sidebar($user);
@endphp

<div x-data="{ open: false }"
     @toggle-menu.window="open = !open"
     @keydown.escape.window="open = false">

    {{-- Fond sombre, mobile uniquement --}}
    <div x-show="open" x-transition.opacity x-cloak
         @click="open = false"
         class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden"></div>

    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200/70 bg-white/90 backdrop-blur-xl transition-transform duration-300 ease-out md:translate-x-0 md:w-64 dark:border-slate-800 dark:bg-slate-900/90">

        {{-- Identité de la salle --}}
        <div class="flex h-16 items-center gap-3 border-b border-slate-200/70 px-5 dark:border-slate-800">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 font-bold text-white shadow-sm">
                {{-- {{ mb_strtoupper(mb_substr($venue->name, 0, 1)) }} --}}
                <x-app-logo class="h-9 w-9" />
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold">{{ $venue->name }}</p>
                <p class="truncate text-xs text-slate-500">{{ $user->role->label() }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5" @click="open = false">
            @foreach($sections as $section)
                <div>
                    @if($section['title'])
                        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $section['title'] }}</p>
                    @endif
                    <ul class="space-y-0.5">
                        @foreach($section['items'] as $item)
                            <li>
                                <x-nav-link :href="route($item['route'])" :icon="$item['icon']" :active="request()->routeIs($item['match'])">
                                    {{ $item['label'] }}
                                </x-nav-link>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div class="border-t border-slate-200/70 px-5 py-3 text-[11px] text-slate-400 dark:border-slate-800">
            {{ config('app.name') }}
        </div>
    </aside>
</div>
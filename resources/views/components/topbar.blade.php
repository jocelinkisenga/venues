@php
    $user = auth()->user();
    $venues = $user->venues()->orderBy('name')->get();
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->implode('');
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/75 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/75">
    <div class="flex h-16 items-center gap-2 px-4 sm:gap-3 sm:px-6 lg:px-8">

        {{-- Ouvre le menu sur mobile --}}
        <button type="button" @click="$dispatch('toggle-menu')" aria-label="Ouvrir le menu"
                class="-ml-2 rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 md:hidden dark:hover:bg-slate-800 dark:hover:text-slate-100">
            <x-ui-icon name="menu" class="h-6 w-6" />
        </button>

        <div class="flex-1"></div>

        {{-- Sélecteur de salle, si l'utilisateur en gère plusieurs --}}
        @if($venues->count() > 1)
            <div x-data="{ open: false }" @click.outside="open = false" class="relative hidden sm:block">
                <button type="button" @click="open = !open" aria-haspopup="true"
                        class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">
                    <x-ui-icon name="building" class="h-4 w-4 text-slate-400" />
                    <span class="max-w-40 truncate">{{ $user->venue->name }}</span>
                    <x-ui-icon name="chevron" class="h-4 w-4 text-slate-400" />
                </button>

                <div x-show="open" x-cloak x-transition.origin.top.right
                     class="absolute right-0 mt-2 w-64 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    @foreach($venues as $venue)
                        <form method="POST" action="{{ route('venues.switch', $venue) }}">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center justify-between gap-3 px-4 py-2 text-left text-sm transition hover:bg-slate-100 dark:hover:bg-slate-800">
                                <span class="truncate">{{ $venue->name }}</span>
                                @if($venue->id === $user->venue_id)
                                    <span class="text-xs font-medium text-amber-600">Active</span>
                                @endif
                            </button>
                        </form>
                    @endforeach
                    <div class="mt-1 border-t border-slate-200 pt-1 dark:border-slate-700">
                        <a href="{{ route('venues') }}" wire:navigate class="block px-4 py-2 text-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">Gérer mes salles</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Thème clair / sombre --}}
<button type="button"
        @click="$store.theme.cycle()"
        :title="'Thème : ' + ({ light: 'clair', dark: 'sombre', system: 'système' })[$store.theme.mode]"
        aria-label="Changer de thème"
        class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-800 dark:hover:text-slate-100">
    <x-ui-icon name="sun" class="h-5 w-5" x-show="$store.theme.resolved === 'dark'" x-cloak />
    <x-ui-icon name="moon" class="h-5 w-5" x-show="$store.theme.resolved === 'light'" x-cloak />
</button>

        {{-- Menu utilisateur --}}
        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button type="button" @click="open = !open" aria-haspopup="true"
                    class="flex items-center gap-2 rounded-xl p-1 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-xs font-semibold text-white shadow-sm">
                    {{ $initials }}
                </span>
                <span class="hidden text-left leading-tight lg:block">
                    <span class="block text-sm font-medium">{{ $user->name }}</span>
                    <span class="block text-xs text-slate-500">{{ $user->role->label() }}</span>
                </span>
            </button>

            <div x-show="open" x-cloak x-transition.origin.top.right
                 class="absolute right-0 mt-2 w-60 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                    <p class="truncate text-sm font-medium">{{ $user->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                </div>

                @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}" wire:navigate
                       class="flex items-center gap-2 px-4 py-2 text-sm transition hover:bg-slate-100 dark:hover:bg-slate-800">
                        <x-ui-icon name="user" class="h-4 w-4 text-slate-400" /> Mon profil
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-red-600 transition hover:bg-red-50 dark:hover:bg-red-900/20">
                        <x-ui-icon name="logout" class="h-4 w-4" /> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
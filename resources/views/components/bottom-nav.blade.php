@php($items = \App\Support\Navigation::mobile(auth()->user()))

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200/70 bg-white/85 backdrop-blur-xl md:hidden dark:border-slate-800 dark:bg-slate-900/85"
     style="padding-bottom: env(safe-area-inset-bottom)">
    <ul class="grid grid-cols-4">
        @foreach($items as $item)
            @php($active = request()->routeIs($item['match']))
            <li>
                <a href="{{ route($item['route']) }}" wire:navigate
                   class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-medium transition {{ $active ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-slate-400' }}">
                    <x-ui-icon :name="$item['icon']" class="h-6 w-6" />
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
        <li>
            <button type="button" @click="$dispatch('toggle-menu')"
                    class="flex w-full flex-col items-center gap-1 py-2.5 text-[11px] font-medium text-slate-500 transition dark:text-slate-400">
                <x-ui-icon name="menu" class="h-6 w-6" />
                Plus
            </button>
        </li>
    </ul>
</nav>
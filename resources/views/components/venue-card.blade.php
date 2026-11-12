@props(['venue', 'href' => null])

@php($cover = $venue->photos->first())

<a href="{{ $href ?? route('explorer.show', $venue->slug) }}"
   {{ $attributes->class([
        'group flex h-full flex-col overflow-hidden rounded-3xl bg-white ring-1 ring-stone-900/5 transition duration-300',
        'hover:-translate-y-0.5 hover:shadow-xl hover:shadow-stone-900/10',
        'dark:bg-stone-900 dark:ring-white/10',
   ]) }}>

    <div class="relative aspect-[4/3] overflow-hidden bg-stone-100 dark:bg-stone-800">
        @if($cover)
            <img src="{{ $cover->url() }}" alt="{{ $venue->name }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.03]">
        @else
            <div class="flex h-full w-full items-center justify-center text-stone-300 dark:text-stone-600">
                <x-ui-icon name="images" class="h-10 w-10" />
            </div>
        @endif

        @if($venue->city)
            <span class="absolute bottom-3 left-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-xs font-medium text-stone-700 shadow-sm dark:bg-stone-900/95 dark:text-stone-200">
                <x-ui-icon name="pin" class="h-3.5 w-3.5" />
                {{ $venue->city }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="truncate text-base font-semibold tracking-tight">{{ $venue->name }}</h3>

        <p class="mt-1.5 line-clamp-2 min-h-[2.5rem] text-sm leading-relaxed text-stone-500 dark:text-stone-400">
            {{ $venue->description }}
        </p>

        <div class="mt-auto flex items-center justify-between gap-3 border-t border-stone-100 pt-4 text-sm dark:border-stone-800">
            <span class="inline-flex items-center gap-1.5 text-stone-600 dark:text-stone-300">
                <x-ui-icon name="users" class="h-4 w-4 text-stone-400" />
                Jusqu'à {{ $venue->max_capacity }}
            </span>

            @if($venue->min_price !== null)
                <span class="text-stone-500 dark:text-stone-400">
                    dès <span class="font-semibold text-stone-900 dark:text-stone-100">{{ $venue->money($venue->min_price) }}</span>
                </span>
            @endif
        </div>
    </div>
</a>
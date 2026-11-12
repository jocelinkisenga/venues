@props(['photos', 'name'])

@php($urls = collect($photos)->map(fn ($p) => $p->url())->values())

@if($urls->isEmpty())
    <div class="flex aspect-[16/9] items-center justify-center rounded-3xl bg-gradient-to-br from-amber-100 to-stone-200 text-stone-400 dark:from-stone-800 dark:to-stone-900">
        <x-ui-icon name="images" class="h-12 w-12" />
    </div>
@else
    <div x-data="{
            open: false,
            index: 0,
            photos: @js($urls->all()),
            show(i) { this.index = i; this.open = true; document.body.style.overflow = 'hidden'; },
            close() { this.open = false; document.body.style.overflow = ''; },
            next() { this.index = (this.index + 1) % this.photos.length; },
            prev() { this.index = (this.index - 1 + this.photos.length) % this.photos.length; },
         }"
         @keydown.escape.window="if (open) close()"
         @keydown.arrow-right.window="if (open) next()"
         @keydown.arrow-left.window="if (open) prev()">

        {{-- Mobile : défilement horizontal avec accroche --}}
        <div class="-mx-4 flex snap-x snap-mandatory gap-3 overflow-x-auto px-4 pb-1 [scrollbar-width:none] sm:hidden [&::-webkit-scrollbar]:hidden">
            @foreach($urls as $i => $url)
                <button type="button" @click="show({{ $i }})"
                        class="w-[86%] shrink-0 snap-center overflow-hidden rounded-2xl bg-stone-200 dark:bg-stone-800">
                    <img src="{{ $url }}" alt="{{ $name }}, photo {{ $i + 1 }}" loading="lazy"
                         class="aspect-[4/3] w-full object-cover">
                </button>
            @endforeach
        </div>

        {{-- Écran large : mosaïque --}}
        <div class="hidden h-[26rem] grid-cols-4 grid-rows-2 gap-3 sm:grid lg:h-[30rem]">
            @foreach($urls->take(5) as $i => $url)
                <button type="button" @click="show({{ $i }})"
                        class="group relative overflow-hidden rounded-2xl bg-stone-200 dark:bg-stone-800 {{ $i === 0 ? 'col-span-2 row-span-2' : '' }}">
                    <img src="{{ $url }}" alt="{{ $name }}, photo {{ $i + 1 }}" loading="lazy"
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-105">

                    @if($i === 4 && $urls->count() > 5)
                        <span class="absolute inset-0 flex items-center justify-center bg-stone-900/55 text-sm font-semibold text-white backdrop-blur-[2px]">
                            +{{ $urls->count() - 5 }} photos
                        </span>
                    @endif
                </button>
            @endforeach
        </div>

        <button type="button" @click="show(0)"
                class="mt-4 inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-stone-50 dark:border-stone-700 dark:bg-stone-900 dark:hover:bg-stone-800">
            <x-ui-icon name="images" class="h-4 w-4 text-stone-400" />
            Voir les {{ $urls->count() }} photo(s)
        </button>

        {{-- Visionneuse plein écran --}}
        <div x-show="open" x-cloak x-transition.opacity
             class="fixed inset-0 z-[80] flex flex-col bg-stone-950/95 backdrop-blur-sm"
             role="dialog" aria-modal="true" aria-label="Galerie photos">

            <div class="flex items-center justify-between px-4 py-4 text-sm text-white/70">
                <span x-text="(index + 1) + ' / ' + photos.length"></span>
                <button type="button" @click="close()" aria-label="Fermer"
                        class="rounded-full p-2 text-white/80 transition hover:bg-white/10 hover:text-white">
                    <x-ui-icon name="x" class="h-6 w-6" />
                </button>
            </div>

            <div class="relative flex flex-1 items-center justify-center px-2 sm:px-16">
                <button type="button" @click="prev()" aria-label="Photo précédente"
                        class="absolute left-2 hidden rounded-full p-3 text-white/80 transition hover:bg-white/10 hover:text-white sm:block">
                    <x-ui-icon name="chevron" class="h-6 w-6 rotate-90" />
                </button>

                <img :src="photos[index]" :alt="'{{ $name }}, photo ' + (index + 1)"
                     class="max-h-[78vh] max-w-full rounded-xl object-contain">

                <button type="button" @click="next()" aria-label="Photo suivante"
                        class="absolute right-2 hidden rounded-full p-3 text-white/80 transition hover:bg-white/10 hover:text-white sm:block">
                    <x-ui-icon name="chevron" class="h-6 w-6 -rotate-90" />
                </button>
            </div>

            {{-- Vignettes --}}
            <div class="flex justify-center gap-2 overflow-x-auto px-4 py-5 [scrollbar-width:none]">
                <template x-for="(src, i) in photos" :key="i">
                    <button type="button" @click="index = i"
                            class="h-14 w-20 shrink-0 overflow-hidden rounded-lg ring-2 transition"
                            :class="index === i ? 'ring-amber-400 opacity-100' : 'ring-transparent opacity-50 hover:opacity-80'">
                        <img :src="src" alt="" class="h-full w-full object-cover">
                    </button>
                </template>
            </div>
        </div>
    </div>
@endif
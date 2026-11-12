@php
    $cell = 'relative min-w-0 flex-1 rounded-2xl px-5 py-3 transition hover:bg-stone-100/70 focus-within:bg-stone-100 dark:hover:bg-stone-800/60 dark:focus-within:bg-stone-800';
    $caption = 'block text-[11px] font-semibold uppercase tracking-wider text-stone-500 dark:text-stone-400';
    $field = 'h-9 w-full bg-transparent text-[15px] font-medium text-stone-900 placeholder:font-normal placeholder:text-stone-400 focus:outline-none dark:text-stone-100';
    $divider = 'my-3 hidden w-px self-stretch bg-stone-200 md:block dark:bg-stone-700';
    $stepper = 'flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:border-stone-400 hover:text-stone-900 dark:border-stone-700 dark:text-stone-300';
    $chip = 'shrink-0 rounded-full border px-4 py-2 text-sm font-medium transition';
    $chipOn = 'border-stone-900 bg-stone-900 text-white dark:border-amber-400 dark:bg-amber-400 dark:text-stone-900';
    $chipOff = 'border-stone-200 bg-white text-stone-600 hover:border-stone-400 hover:text-stone-900 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300';
@endphp

<div>
    {{-- Accroche et recherche --}}
    <section class="relative isolate">
        <div class="absolute inset-x-0 top-0 -z-10 h-[32rem] bg-gradient-to-b from-amber-100/70 via-stone-50 to-stone-50 dark:from-amber-400/5 dark:via-stone-950 dark:to-stone-950"></div>

        <div class="mx-auto max-w-5xl px-4 pb-14 pt-14 text-center sm:px-6 sm:pt-20">
            <h1 class="text-balance text-4xl font-semibold tracking-tight sm:text-5xl">Trouvez la salle de vos événements</h1>
            <p class="mx-auto mt-4 max-w-xl text-pretty text-stone-500 dark:text-stone-400">
                Comparez les espaces, vérifiez les dates disponibles et envoyez votre demande en quelques clics.
            </p>
            {{-- recjecje --
            <form wire:submit="askAssistant" class="mx-auto mt-10 max-w-4xl">
    <label for="ask" class="sr-only">Décrivez votre événement</label>
    <div class="flex items-center gap-2 rounded-2xl bg-white p-2 ring-1 ring-stone-900/5 shadow-sm dark:bg-stone-900 dark:ring-white/10">
        <x-ui-icon name="sparkles" class="ml-3 h-5 w-5 shrink-0 text-amber-500" />
        <input id="ask" type="text" wire:model="ask"
               placeholder="Ex : salle pour 150 personnes à Lubumbashi le 14 décembre, avec parking"
               class="h-11 min-w-0 flex-1 border-0 bg-transparent text-[15px] placeholder:text-stone-400 focus:ring-0">
        <button type="submit" wire:loading.attr="disabled" wire:target="askAssistant"
                class="h-11 shrink-0 rounded-xl bg-stone-900 px-5 text-sm font-semibold text-white transition hover:bg-stone-800 disabled:opacity-60 dark:bg-amber-400 dark:text-stone-900">
            <span wire:loading.remove wire:target="askAssistant">Chercher</span>
            <span wire:loading wire:target="askAssistant">Analyse…</span>
        </button>
    </div>
    @error('ask') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</form>
{{-- recherche --}}
            <form onsubmit="return false"
                  class="mx-auto mt-10 max-w-4xl rounded-3xl bg-white p-2 text-left shadow-xl shadow-stone-900/[0.06] ring-1 ring-stone-900/5 md:rounded-full dark:bg-stone-900 dark:ring-white/10">
                <div class="flex flex-col md:flex-row md:items-stretch">

                    <label class="{{ $cell }} md:rounded-l-full">
                        <span class="{{ $caption }}">Lieu</span>
                        <input type="search" wire:model.live.debounce.300ms="search"
                               placeholder="Ville ou nom de salle" aria-label="Lieu" class="{{ $field }}">
                    </label>

                    <span class="{{ $divider }}"></span>

                    <label class="{{ $cell }}">
                        <span class="{{ $caption }}">Date</span>
                        <input type="date" wire:model.live="date" min="{{ now()->format('Y-m-d') }}"
                               aria-label="Date de l'événement" class="{{ $field }}">
                    </label>

                    <span class="{{ $divider }}"></span>

                    <div class="{{ $cell }}">
                        <span class="{{ $caption }}">Invités</span>
                        <div class="mt-0.5 flex items-center gap-2">
                            <button type="button" wire:click="decrementGuests" aria-label="Retirer 10 invités" class="{{ $stepper }}">
                                <x-ui-icon name="minus" class="h-3.5 w-3.5" />
                            </button>
                            <input type="number" min="0" wire:model.live.debounce.400ms="guests" placeholder="Tous"
                                   aria-label="Nombre d'invités" class="{{ $field }} w-16 text-center">
                            <button type="button" wire:click="incrementGuests" aria-label="Ajouter 10 invités" class="{{ $stepper }}">
                                <x-ui-icon name="plus" class="h-3.5 w-3.5" />
                            </button>
                        </div>
                    </div>

                    <div class="p-1 md:pl-2">
                        <a href="#resultats"
                           class="flex h-12 w-full items-center justify-center gap-2 rounded-full bg-stone-900 px-6 text-sm font-semibold text-white transition hover:bg-stone-800 md:w-auto dark:bg-amber-400 dark:text-stone-900 dark:hover:bg-amber-300">
                            <x-ui-icon name="search" class="h-4 w-4" />
                            Rechercher
                        </a>
                    </div>
                </div>
            </form>

            {{-- Villes --}}
            @if($cities->isNotEmpty())
                <div class="mx-auto mt-6 flex max-w-4xl gap-2 overflow-x-auto pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <button type="button" wire:click="$set('city', '')" class="{{ $chip }} {{ $city === '' ? $chipOn : $chipOff }}">
                        Toutes les villes
                    </button>
                    @foreach($cities as $name)
                        <button type="button" wire:click="$set('city', @js($name))" class="{{ $chip }} {{ $city === $name ? $chipOn : $chipOff }}">
                            {{ $name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Résultats --}}
    <section id="resultats" class="scroll-mt-24 mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 border-b border-stone-200/80 pb-5 sm:flex-row sm:items-center sm:justify-between dark:border-stone-800">
            <div>
                <p class="text-base font-semibold" wire:loading.remove>
                    {{ $venues->count() }} {{ $venues->count() > 1 ? 'salles' : 'salle' }}
                    @if($dateLabel)
                        <span class="font-normal text-stone-500">disponibles le {{ $dateLabel }}</span>
                    @endif
                </p>
                <p class="text-base text-stone-400" wire:loading>Recherche en cours…</p>
            </div>

            <div class="flex items-center gap-3">
                @if($hasFilters)
                    <button type="button" wire:click="resetFilters" class="text-sm font-medium text-stone-500 underline-offset-4 hover:text-stone-900 hover:underline dark:hover:text-stone-200">
                        Effacer les filtres
                    </button>
                @endif

                <label class="flex items-center gap-2 text-sm text-stone-500">
                    Trier
                    <select wire:model.live="sort" class="rounded-xl border border-stone-200 bg-white py-2 pl-3 pr-8 text-sm font-medium text-stone-800 focus:border-stone-900 focus:outline-none dark:border-stone-700 dark:bg-stone-900 dark:text-stone-100">
                        @foreach($sorts as $key => $sortLabel)
                            <option value="{{ $key }}">{{ $sortLabel }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>

        <div wire:loading.class="opacity-50" class="mt-8 grid gap-x-6 gap-y-10 transition-opacity duration-200 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($venues as $venue)
                <x-venue-card :venue="$venue" wire:key="venue-{{ $venue->id }}"
                    :href="route('explorer.show', array_merge(['slug' => $venue->slug], $linkQuery))" />
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-20 text-center dark:border-stone-700 dark:bg-stone-900/40">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-stone-100 text-stone-400 dark:bg-stone-800">
                        <x-ui-icon name="search" class="h-6 w-6" />
                    </span>
                    <p class="mt-5 text-lg font-semibold">Aucune salle ne correspond</p>
                    <p class="mx-auto mt-2 max-w-sm text-sm text-stone-500">
                        Essayez une autre date, moins d'invités ou une autre ville.
                    </p>
                    <button type="button" wire:click="resetFilters"
                            class="mt-6 rounded-full bg-stone-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-700 dark:bg-amber-400 dark:text-stone-900">
                        Voir toutes les salles
                    </button>
                </div>
            @endforelse
        </div>
    </section>
</div>
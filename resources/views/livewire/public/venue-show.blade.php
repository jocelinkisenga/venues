@php
    $card = 'rounded-3xl bg-white ring-1 ring-stone-900/5 dark:bg-stone-900 dark:ring-white/10';
    $label = 'mb-2 block text-sm font-medium text-stone-700 dark:text-stone-300';
    $input = 'h-12 w-full rounded-xl border border-stone-200 bg-white px-4 text-[15px] transition focus:border-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-900/10 dark:border-stone-700 dark:bg-stone-800 dark:focus:border-amber-400 dark:focus:ring-amber-400/20';
    $error = 'mt-2 text-xs font-medium text-red-600 dark:text-red-400';
    $choice = 'group relative flex cursor-pointer items-center gap-4 rounded-2xl border border-stone-200 bg-white p-4 transition hover:border-stone-400 has-[:checked]:border-stone-900 has-[:checked]:ring-1 has-[:checked]:ring-stone-900 dark:border-stone-700 dark:bg-stone-900 dark:has-[:checked]:border-amber-400 dark:has-[:checked]:ring-amber-400';
    $dot = 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-stone-300 text-white transition peer-checked:border-stone-900 peer-checked:bg-stone-900 dark:border-stone-600 dark:peer-checked:border-amber-400 dark:peer-checked:bg-amber-400 dark:peer-checked:text-stone-900';
    $pill = 'rounded-xl border px-3 py-3 text-left text-sm transition';
    $pillOn = 'border-stone-900 bg-stone-900 text-white dark:border-amber-400 dark:bg-amber-400 dark:text-stone-900';
    $pillOff = 'border-stone-200 bg-white hover:border-stone-400 dark:border-stone-700 dark:bg-stone-900';
    $contact = 'inline-flex items-center gap-2 rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-medium transition hover:border-stone-400 dark:border-stone-700 dark:bg-stone-900';
@endphp

<div class="mx-auto max-w-7xl px-4 pb-32 pt-6 sm:px-6 lg:px-8 lg:pb-20">

    <a href="{{ route('explorer') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-stone-500 transition hover:text-stone-900 dark:hover:text-stone-200">
        <x-ui-icon name="arrow-left" class="h-4 w-4" />
        Toutes les salles
    </a>

    <header class="mt-6 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-stone-500">
                @if($venue->city)
                    <span class="inline-flex items-center gap-1.5"><x-ui-icon name="pin" class="h-4 w-4" />{{ $venue->city }}</span>
                @endif
                @if($venue->max_capacity ?? false)
                    <span class="inline-flex items-center gap-1.5"><x-ui-icon name="users" class="h-4 w-4" />Jusqu'à {{ $venue->spaces()->active()->max('capacity') }} personnes</span>
                @endif
            </div>
            <h1 class="mt-2 text-balance text-3xl font-semibold tracking-tight sm:text-4xl">{{ $venue->name }}</h1>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($venue->contact_phone)
                <a href="tel:{{ preg_replace('/\s+/', '', $venue->contact_phone) }}" class="{{ $contact }}">
                    <x-ui-icon name="phone" class="h-4 w-4 text-stone-400" /> Appeler
                </a>
            @endif
            @if($venue->email)
                <a href="mailto:{{ $venue->email }}" class="{{ $contact }}">
                    <x-ui-icon name="mail" class="h-4 w-4 text-stone-400" /> Écrire
                </a>
            @endif
        </div>
    </header>

    <div class="mt-8">
        <x-venue-gallery :photos="$venue->photos" :name="$venue->name" />
    </div>

    <div class="mt-12 grid gap-12 lg:grid-cols-3 lg:gap-16">

        {{-- Informations --}}
        <div class="space-y-14 lg:col-span-2">
            @if($venue->description)
                <section>
                    <h2 class="text-xl font-semibold tracking-tight">À propos de cette salle</h2>
                    <p class="mt-4 whitespace-pre-line text-[15px] leading-7 text-stone-600 dark:text-stone-300">{{ $venue->description }}</p>
                </section>
            @endif

            @if(! empty($venue->amenities))
                <section>
                    <h2 class="text-xl font-semibold tracking-tight">Équipements</h2>
                    <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach($venue->amenities as $key)
                            <li class="flex items-center gap-3 text-[15px]">
                                <x-ui-icon name="check" class="h-5 w-5 text-stone-900 dark:text-amber-400" />
                                {{ config('venue.amenities.'.$key, $key) }}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section>
                <h2 class="text-xl font-semibold tracking-tight">Espaces</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    @forelse($spaces as $space)
                        <div class="{{ $card }} p-5">
                            <p class="font-semibold">{{ $space->name }}</p>
                            <p class="mt-3 flex items-baseline gap-1.5">
                                <span class="text-2xl font-semibold tracking-tight">{{ $space->capacity }}</span>
                                <span class="text-sm text-stone-500">personnes maximum</span>
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-stone-500">Aucun espace disponible actuellement.</p>
                    @endforelse
                </div>
            </section>

            @if($venue->address || ($venue->latitude && $venue->longitude))
                <section>
                    <h2 class="text-xl font-semibold tracking-tight">Où se trouve la salle</h2>
                    <div class="{{ $card }} mt-5 flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <x-ui-icon name="pin" class="mt-0.5 h-5 w-5 shrink-0 text-stone-400" />
                            <div>
                                @if($venue->address)<p class="text-sm font-medium">{{ $venue->address }}</p>@endif
                                @if($venue->city)<p class="text-sm text-stone-500">{{ $venue->city }}</p>@endif
                            </div>
                        </div>
                        @if($venue->latitude && $venue->longitude)
                            <a href="https://www.openstreetmap.org/?mlat={{ $venue->latitude }}&mlon={{ $venue->longitude }}#map=16/{{ $venue->latitude }}/{{ $venue->longitude }}"
                               target="_blank" rel="noopener" class="{{ $contact }} self-start sm:self-auto">
                                Voir sur la carte
                            </a>
                        @endif
                    </div>
                </section>
            @endif
        </div>

        {{-- Réservation --}}
        <aside id="reservation" class="scroll-mt-24 lg:sticky lg:top-24 lg:h-fit">
            <div class="{{ $card }} overflow-hidden shadow-xl shadow-stone-900/[0.06]">

                {{-- En-tête : progression --}}
                <div class="border-b border-stone-100 p-6 dark:border-stone-800">
                    <div class="flex items-baseline justify-between gap-4">
                        <h2 class="text-lg font-semibold tracking-tight">Demander une réservation</h2>
                        @if($minPrice !== null)
                            <p class="text-xs text-stone-500">dès <span class="font-semibold text-stone-900 dark:text-stone-100">{{ $venue->money($minPrice) }}</span></p>
                        @endif
                    </div>

                    <ol class="mt-5 flex items-center">
                        @foreach($steps as $n => $title)
                            <li class="flex items-center {{ ! $loop->last ? 'flex-1' : '' }}">
                                <button type="button" wire:click="goTo({{ $n }})" @disabled($n > $step)
                                        aria-label="Étape {{ $n }} : {{ $title }}"
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold transition
                                               {{ $n <= $step ? 'bg-stone-900 text-white dark:bg-amber-400 dark:text-stone-900' : 'bg-stone-100 text-stone-400 dark:bg-stone-800' }}
                                               {{ $n < $step ? 'cursor-pointer' : 'cursor-default' }}">
                                    @if($n < $step)
                                        <x-ui-icon name="check" class="h-4 w-4" />
                                    @else
                                        {{ $n }}
                                    @endif
                                </button>
                                @unless($loop->last)
                                    <span class="mx-2 h-px flex-1 {{ $n < $step ? 'bg-stone-900 dark:bg-amber-400' : 'bg-stone-200 dark:bg-stone-700' }}"></span>
                                @endunless
                            </li>
                        @endforeach
                    </ol>
                    <p class="mt-3 text-sm font-medium">{{ $steps[$step] }}</p>
                </div>

                <form wire:submit="handleSubmit" class="p-6">

                    {{-- Étape 1 : date, formule, invités --}}
                    @if($step === 1)
                        <div class="space-y-7">
                            <div>
                                <label for="eventDate" class="{{ $label }}">Date de l'événement</label>
                                <input id="eventDate" type="date" wire:model.live="eventDate"
                                       min="{{ now()->addDay()->format('Y-m-d') }}" class="{{ $input }}">
                                @error('eventDate') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <span class="{{ $label }}">Formule</span>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" wire:click="applyPreset('evening')" aria-pressed="{{ $preset === 'evening' ? 'true' : 'false' }}"
                                            class="{{ $pill }} {{ $preset === 'evening' ? $pillOn : $pillOff }}">
                                        <span class="block font-semibold">Soirée</span>
                                        <span class="block text-xs opacity-70">16h → 6h</span>
                                    </button>
                                    <button type="button" wire:click="applyPreset('day')" aria-pressed="{{ $preset === 'day' ? 'true' : 'false' }}"
                                            class="{{ $pill }} {{ $preset === 'day' ? $pillOn : $pillOff }}">
                                        <span class="block font-semibold">Journée</span>
                                        <span class="block text-xs opacity-70">10h → 18h</span>
                                    </button>
                                    <button type="button" wire:click="applyPreset('multi')" aria-pressed="{{ $preset === 'multi' ? 'true' : 'false' }}"
                                            class="{{ $pill }} {{ $preset === 'multi' ? $pillOn : $pillOff }}">
                                        <span class="block font-semibold">Plusieurs jours</span>
                                        <span class="block text-xs opacity-70">Mariage, séminaire</span>
                                    </button>
                                </div>

                                @if($preset === 'multi')
                                    <div class="mt-3 flex items-center justify-between rounded-xl bg-stone-50 px-4 py-3 text-sm dark:bg-stone-800">
                                        <span>Nombre de jours</span>
                                        <select wire:model.live="days" class="rounded-lg border border-stone-200 bg-white px-2 py-1 dark:border-stone-700 dark:bg-stone-900">
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @endif

                                <details class="group mt-4">
                                    <summary class="flex cursor-pointer list-none items-center gap-2 text-sm font-medium text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white">
                                        <x-ui-icon name="pencil" class="h-4 w-4" />
                                        Ajuster les horaires
                                        <x-ui-icon name="chevron" class="ml-auto h-4 w-4 transition group-open:rotate-180" />
                                    </summary>
                                    <div class="mt-4 grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="starts_at" class="{{ $label }}">Début</label>
                                            <input id="starts_at" type="datetime-local" wire:model.live="starts_at" class="{{ $input }}">
                                        </div>
                                        <div>
                                            <label for="ends_at" class="{{ $label }}">Fin</label>
                                            <input id="ends_at" type="datetime-local" wire:model.live="ends_at" class="{{ $input }}">
                                        </div>
                                    </div>
                                    @error('starts_at') <p class="{{ $error }}">{{ $message }}</p> @enderror
                                    @error('ends_at') <p class="{{ $error }}">{{ $message }}</p> @enderror
                                </details>
                            </div>

                            <div>
                                <span class="{{ $label }}">Nombre d'invités</span>
                                <div class="flex items-center justify-between rounded-2xl border border-stone-200 p-2 dark:border-stone-700">
                                    <button type="button" wire:click="decrementGuests" aria-label="Retirer 10 invités"
                                            class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:border-stone-400 dark:border-stone-700 dark:text-stone-300">
                                        <x-ui-icon name="minus" class="h-4 w-4" />
                                    </button>
                                    <input type="number" min="1" wire:model.live.debounce.400ms="guests_count" aria-label="Nombre d'invités"
                                           class="w-24 border-0 bg-transparent text-center text-xl font-semibold focus:outline-none focus:ring-0 dark:text-white">
                                    <button type="button" wire:click="incrementGuests" aria-label="Ajouter 10 invités"
                                            class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:border-stone-400 dark:border-stone-700 dark:text-stone-300">
                                        <x-ui-icon name="plus" class="h-4 w-4" />
                                    </button>
                                </div>
                                @error('guests_count') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="event_type" class="{{ $label }}">Type d'événement</label>
                                <select id="event_type" wire:model="event_type" class="{{ $input }}">
                                    @foreach($eventTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('event_type') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Étape 2 : espace et forfait --}}
                    @if($step === 2)
                        <div class="space-y-7">
                            <fieldset>
                                <legend class="{{ $label }}">Espace</legend>
                                <div class="grid gap-2.5">
                                    @foreach($spaces as $space)
                                        <label class="{{ $choice }}">
                                            <input type="radio" wire:model.live="space_id" value="{{ $space->id }}" class="peer sr-only">
                                            <span class="min-w-0 flex-1">
                                                <span class="block font-semibold">{{ $space->name }}</span>
                                                <span class="block text-sm text-stone-500">Jusqu'à {{ $space->capacity }} personnes</span>
                                            </span>
                                            <span class="{{ $dot }}"><x-ui-icon name="check" class="h-3 w-3 opacity-0 peer-checked:opacity-100" /></span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('space_id') <p class="{{ $error }}">{{ $message }}</p> @enderror
                                @if($capacityWarning)
                                    <p class="{{ $error }}">Ce nombre d'invités dépasse la capacité de cet espace ({{ $selectedSpace->capacity }}).</p>
                                @endif
                            </fieldset>

                            <fieldset>
                                <legend class="{{ $label }}">Forfait</legend>
                                <div class="grid gap-2.5">
                                    <label class="{{ $choice }}">
                                        <input type="radio" wire:model="package_id" value="" class="peer sr-only">
                                        <span class="min-w-0 flex-1">
                                            <span class="block font-semibold">Sans forfait</span>
                                            <span class="block text-sm text-stone-500">Location de l'espace seule</span>
                                        </span>
                                        <span class="{{ $dot }}"><x-ui-icon name="check" class="h-3 w-3 opacity-0 peer-checked:opacity-100" /></span>
                                    </label>

                                    @forelse($packages as $package)
                                        <label class="{{ $choice }}">
                                            <input type="radio" wire:model="package_id" value="{{ $package->id }}" class="peer sr-only">
                                            <span class="min-w-0 flex-1">
                                                <span class="block font-semibold">{{ $package->name }}</span>
                                                <span class="block text-sm text-stone-500">
                                                    {{ $package->time_type === 'multi_jours' ? $package->days.' jour(s)' : 'Soirée et nuit' }}
                                                    · {{ $package->included_hours }} h incluses
                                                </span>
                                            </span>
                                            <span class="text-right">
                                                <span class="block text-sm font-semibold">{{ $venue->money($package->base_price) }}</span>
                                                <span class="{{ $dot }} ml-auto mt-1"><x-ui-icon name="check" class="h-3 w-3 opacity-0 peer-checked:opacity-100" /></span>
                                            </span>
                                        </label>
                                    @empty
                                        <p class="rounded-2xl bg-stone-50 p-4 text-sm text-stone-500 dark:bg-stone-800">
                                            Aucun forfait pour cet espace : le devis sera établi sur mesure.
                                        </p>
                                    @endforelse
                                </div>
                                @error('package_id') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </fieldset>
                        </div>
                    @endif

                    {{-- Étape 3 : coordonnées --}}
                    @if($step === 3)
                        <div class="space-y-5">
                            <div>
                                <label for="client_name" class="{{ $label }}">Nom complet</label>
                                <input id="client_name" wire:model="client_name" autocomplete="name" class="{{ $input }}">
                                @error('client_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="client_phone" class="{{ $label }}">Téléphone ou WhatsApp</label>
                                <input id="client_phone" type="tel" wire:model="client_phone" placeholder="+243 81 234 5678"
                                       autocomplete="tel" class="{{ $input }}">
                                @error('client_phone') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="client_email" class="{{ $label }}">Email <span class="font-normal text-stone-400">(facultatif)</span></label>
                                <input id="client_email" type="email" wire:model="client_email" autocomplete="email" class="{{ $input }}">
                                @error('client_email') <p class="{{ $error }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="message" class="{{ $label }}">Précisions <span class="font-normal text-stone-400">(facultatif)</span></label>
                                <textarea id="message" wire:model="message" rows="3"
                                          class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-[15px] transition focus:border-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-900/10 dark:border-stone-700 dark:bg-stone-800 dark:focus:border-amber-400"></textarea>
                            </div>
                        </div>

                        {{-- Champ piège --}}
                        <div class="hidden" aria-hidden="true">
                            <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
                        </div>
                    @endif

                    @error('submit')
                        <div class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-700 dark:bg-red-400/10 dark:text-red-300">{{ $message }}</div>
                    @enderror

                    <div class="mt-8 flex items-center gap-3">
                        @if($step > 1)
                            <button type="button" wire:click="back"
                                    class="h-12 rounded-xl px-5 text-sm font-semibold text-stone-600 transition hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-800">
                                Retour
                            </button>
                        @endif

                        <button type="submit" wire:loading.attr="disabled" wire:target="handleSubmit"
                                class="flex h-12 flex-1 items-center justify-center rounded-xl bg-stone-900 text-sm font-semibold text-white transition hover:bg-stone-800 disabled:opacity-60 dark:bg-amber-400 dark:text-stone-900 dark:hover:bg-amber-300">
                            <span wire:loading.remove wire:target="handleSubmit">
                                {{ $step < 3 ? 'Continuer' : 'Envoyer ma demande' }}
                            </span>
                            <span wire:loading wire:target="handleSubmit">Veuillez patienter…</span>
                        </button>
                    </div>
                </form>

                {{-- Récapitulatif --}}
                <div class="border-t border-stone-100 bg-stone-50/70 p-6 dark:border-stone-800 dark:bg-stone-900/60">
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-stone-500">Date</dt>
                            <dd class="text-right font-medium capitalize">{{ $summary['date'] ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-stone-500">Horaires</dt>
                            <dd class="text-right font-medium">{{ $summary['time'] ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-stone-500">Invités</dt>
                            <dd class="font-medium">{{ $guests_count }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-stone-500">Espace</dt>
                            <dd class="text-right font-medium">{{ $selectedSpace?->name ?? 'À choisir' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-stone-500">Forfait</dt>
                            <dd class="text-right font-medium">{{ $selectedPackage?->name ?? 'Sans forfait' }}</dd>
                        </div>
                        <div class="flex items-end justify-between gap-4 border-t border-stone-200 pt-4 dark:border-stone-700">
                            <dt class="font-semibold">Estimation</dt>
                            <dd class="text-right text-xl font-semibold tracking-tight">{{ $summary['estimate'] ?? 'Sur devis' }}</dd>
                        </div>
                    </dl>
                    <p class="mt-4 text-xs leading-relaxed text-stone-400">
                        Estimation indicative. La salle confirme le montant définitif dans son devis. Aucun paiement n'est demandé à cette étape.
                    </p>
                </div>
            </div>
        </aside>
    </div>

    {{-- Barre d'action mobile --}}
    <div class="fixed inset-x-0 bottom-0 z-40 border-t border-stone-200/70 bg-white/95 px-4 pt-3 backdrop-blur-xl lg:hidden dark:border-stone-800 dark:bg-stone-950/95"
         style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom))">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-xs text-stone-500">Estimation</p>
                <p class="truncate text-base font-semibold">{{ $summary['estimate'] ?? 'Sur devis' }}</p>
            </div>
            <a href="#reservation"
               class="inline-flex shrink-0 items-center rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-stone-800 dark:bg-amber-400 dark:text-stone-900">
                Réserver
            </a>
        </div>
    </div>
</div>
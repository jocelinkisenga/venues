@php
    $card = 'rounded-2xl bg-white p-5 ring-1 ring-stone-900/5 dark:bg-stone-900 dark:ring-white/10';
    $field = 'w-full rounded-xl border border-stone-200 bg-white px-3.5 py-2.5 text-sm transition focus:border-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-900/10 dark:border-stone-700 dark:bg-stone-800 dark:focus:border-amber-400';
    $btn = 'inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-800 disabled:cursor-not-allowed disabled:opacity-40 dark:bg-amber-400 dark:text-stone-900 dark:hover:bg-amber-300';
    $btnGhost = 'inline-flex items-center justify-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium transition hover:bg-stone-50 dark:border-stone-700 dark:bg-stone-900 dark:hover:bg-stone-800';

    $statusClass = fn (string $s) => match ($s) {
        'full' => 'border-emerald-500 bg-emerald-50 text-emerald-900 dark:bg-emerald-400/10 dark:text-emerald-200',
        'partial' => 'border-amber-400 bg-amber-50 text-amber-900 dark:bg-amber-400/10 dark:text-amber-200',
        'over' => 'border-red-500 bg-red-50 text-red-900 dark:bg-red-400/10 dark:text-red-200',
        default => 'border-stone-300 bg-white text-stone-700 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-200',
    };
    $statusLabel = fn (string $s) => ['full' => 'Complète', 'partial' => 'Incomplète', 'over' => 'Dépassée', 'empty' => 'Vide'][$s] ?? $s;
    $statusDot = fn (string $s) => match ($s) {
        'full' => 'bg-emerald-500', 'partial' => 'bg-amber-400', 'over' => 'bg-red-500', default => 'bg-stone-300',
    };
    $rsvpBadge = fn (string $s) => $s === 'confirmed'
        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300'
        : 'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300';
    $rsvpLabel = fn (string $s) => $s === 'confirmed' ? 'Confirmé' : 'En attente';
@endphp

<div class="space-y-6" @unless($canEdit) wire:poll.15s @endunless>

    <x-page-header
        title="Plan de salle"
        :subtitle="$booking->reference.' · '.$booking->event_type.' · '.$booking->starts_at->format('d/m/Y H:i').' · version '.$plan->revision"
        back="{{ route('bookings.quote', $booking) }}" backLabel="Devis">
        <x-slot:actions>
            <button type="button" onclick="window.print()" class="{{ $btnGhost }} print:hidden">
                <x-ui-icon name="print" class="h-4 w-4" /> Imprimer
            </button>

            @if($plan->published_at)
                <span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-3 py-2.5 text-sm font-medium text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">
                    <x-ui-icon name="check" class="h-4 w-4" />
                    Validé {{ $plan->published_at->format('d/m à H:i') }}
                </span>
            @elseif($canEdit)
                <button wire:click="publish" class="{{ $btn }}">
                    <x-ui-icon name="check" class="h-4 w-4" /> Valider le plan
                </button>
            @else
                <span class="rounded-xl bg-amber-50 px-3 py-2.5 text-sm font-medium text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">En préparation</span>
            @endif
        </x-slot:actions>
    </x-page-header>

    {{-- Indicateurs --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
        @foreach([
            ['Tables complètes', $totals['full'].' / '.$totals['tables']],
            ['Places occupées', $totals['seated'].' / '.$totals['expected']],
            ['Non placés', $totals['unseatedPeople']],
            ['Tables incomplètes', $totals['partial']],
        ] as [$label, $value])
            <div class="{{ $card }} p-4">
                <p class="text-xs font-medium text-stone-500">{{ $label }}</p>
                <p class="mt-1 text-2xl font-semibold tracking-tight tabular-nums">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    @if($notice)
        <p class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">{{ $notice }}</p>
    @endif
    @if($error)
        <p class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-400/10 dark:text-red-300">{{ $error }}</p>
    @endif

    <div class="grid gap-6 {{ $canEdit ? 'lg:grid-cols-[17rem_minmax(0,1fr)_18rem]' : 'lg:grid-cols-[minmax(0,1fr)_18rem]' }}">

        {{-- Outils (édition) --}}
        @if($canEdit)
            <aside class="order-3 space-y-5 print:hidden lg:order-none">
                <div class="{{ $card }} space-y-3">
                    <h2 class="text-sm font-semibold">Ajouter une table</h2>
                    <input wire:model="newName" placeholder="Nom (facultatif)" class="{{ $field }}">
                    <div class="grid grid-cols-2 gap-2">
                        <select wire:model="newShape" class="{{ $field }}">
                            @foreach($shapes as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                        </select>
                        <input type="number" min="1" max="30" wire:model="newSeats" class="{{ $field }}" aria-label="Places">
                    </div>
                    <select wire:model="newCategory" class="{{ $field }}">
                        @foreach($categories as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                    </select>
                    <button wire:click="addTable" class="{{ $btn }} w-full">Ajouter la table</button>
                </div>

                <div class="{{ $card }} space-y-3">
    <h2 class="text-sm font-semibold">Réorganiser</h2>
    <p class="text-xs leading-relaxed text-stone-500">
        Aligne toutes les tables en rangées. Les convives restent placés.
    </p>
    <select wire:model="arrangePerRow" class="{{ $field }}" aria-label="Tables par rangée">
        <option value="0">Automatique</option>
        <option value="3">3 par rangée</option>
        <option value="4">4 par rangée</option>
        <option value="5">5 par rangée</option>
        <option value="6">6 par rangée</option>
        <option value="8">8 par rangée</option>
    </select>
    <button wire:click="arrangeTables" class="{{ $btnGhost }} w-full">Aligner en rangées</button>
</div>


                <div class="{{ $card }} space-y-3">
                    <h2 class="text-sm font-semibold">Générer le plan</h2>
                    <p class="text-xs leading-relaxed text-stone-500">
                        Crée les tables pour {{ $booking->guests_count }} personnes. Possible seulement si aucun convive n'est encore placé.
                    </p>
                    <select wire:model="layout" class="{{ $field }}">
                        @foreach($layouts as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                    </select>
                    <input type="number" min="1" max="30" wire:model="layoutSeats" class="{{ $field }}" aria-label="Places par table">
                    <button wire:click="applyLayout" wire:confirm="Générer un nouveau plan remplacera les tables actuelles. Continuer ?"
                            class="{{ $btnGhost }} w-full">Générer le plan</button>
                </div>

                <div class="{{ $card }} space-y-3">
                    <h2 class="text-sm font-semibold">Importer la liste</h2>
                    <p class="text-xs leading-relaxed text-stone-500">
                        Une ligne par convive : <code class="rounded bg-stone-100 px-1 dark:bg-stone-800">Nom ; téléphone ; table</code>
                    </p>
                    <textarea wire:model="importText" rows="5" placeholder="Jean Kabila ; 0812345678 ; Table 1"
                              class="{{ $field }} font-mono text-xs"></textarea>
                    @error('importText') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    <button wire:click="importList" class="{{ $btnGhost }} w-full">
                        <x-ui-icon name="upload" class="h-4 w-4" /> Importer
                    </button>

                    @if($importReport)
                        <div class="space-y-1 rounded-xl bg-stone-50 p-3 text-xs dark:bg-stone-800">
                            <p><strong>{{ $importReport['created'] }}</strong> créé(s), <strong>{{ $importReport['updated'] }}</strong> mis à jour, <strong>{{ $importReport['seated'] }}</strong> placé(s).</p>
                            @if($importReport['unknown_tables'])
                                <p class="text-amber-700 dark:text-amber-300">Tables introuvables : {{ implode(', ', $importReport['unknown_tables']) }}</p>
                            @endif
                            @if($importReport['full_tables'])
                                <p class="text-red-700 dark:text-red-300">Tables complètes : {{ implode(', ', $importReport['full_tables']) }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </aside>
        @endif

        {{-- Plan --}}
        <section class="order-1 min-w-0 space-y-3 lg:order-none">
            <div class="relative overflow-auto rounded-3xl bg-stone-100 ring-1 ring-stone-900/5 dark:bg-stone-900 dark:ring-white/10"
                 style="height: min(68vh, 660px)">
                <div class="relative"
                     style="width: {{ $plan->width }}px; height: {{ $plan->height }}px;
                            background-image: radial-gradient(circle, rgb(120 113 108 / 0.2) 1px, transparent 1px);
                            background-size: 20px 20px;">

                    {{-- @foreach($tables as $t)
                        @php($isOpen = $modal && $modal['id'] === $t['id'])
                        <div wire:key="table-{{ $t['id'] }}"
                             @if($canEdit)
                                 x-data="seatTable({{ $t['id'] }}, {{ $t['x'] }}, {{ $t['y'] }})"
                                 @pointerdown="start($event)"
                                 @pointermove.window="move($event)"
                                 @pointerup.window="end()"
                                 @click="if (!moved) $wire.selectTable({{ $t['id'] }})"
                                 :style="`left:${x}px;top:${y}px;width:{{ $t['width'] }}px;height:{{ $t['height'] }}px`"
                             @else
                                 wire:click="selectTable({{ $t['id'] }})"
                                 style="left: {{ $t['x'] }}px; top: {{ $t['y'] }}px; width: {{ $t['width'] }}px; height: {{ $t['height'] }}px"
                             @endif
                             role="button" tabindex="0"
                             aria-label="Table {{ $t['name'] }}, {{ $t['used'] }} sur {{ $t['seats'] }} places"
                             class="absolute flex cursor-pointer select-none flex-col items-center justify-center border-2 px-2 text-center shadow-sm transition-shadow duration-150 hover:shadow-md
                                    {{ $t['shape'] === 'round' ? 'rounded-full' : 'rounded-2xl' }}
                                    {{ $statusClass($t['status']) }}
                                    {{ $isOpen ? 'ring-2 ring-stone-900 ring-offset-2 dark:ring-amber-400 dark:ring-offset-stone-900' : '' }}
                                    {{ $canEdit ? 'touch-none' : '' }}">
                            <span class="max-w-full truncate text-sm font-semibold">{{ $t['name'] }}</span>
                            <span class="text-xs tabular-nums opacity-80">{{ $t['used'] }}/{{ $t['seats'] }}</span>
                            @if($t['category'] !== 'standard')
                                <span class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide opacity-70">{{ $categories[$t['category']] ?? '' }}</span>
                            @endif
                        </div>
                    @endforeach --}}

                    @foreach($tables as $t)
    @php($isOpen = $modal && $modal['id'] === $t['id'])
    <div wire:key="table-{{ $t['id'] }}"
         @if($canEdit)
             x-data="seatTable({{ $t['id'] }})"
             @pointerdown="start($event)"
             @pointermove.window="move($event)"
             @pointerup.window="end()"
             @click="select()"
         @else
             wire:click="selectTable({{ $t['id'] }})"
         @endif
         role="button" tabindex="0"
         aria-label="Table {{ $t['name'] }}, {{ $t['used'] }} sur {{ $t['seats'] }} places"
         style="left: {{ $t['x'] }}px; top: {{ $t['y'] }}px; width: {{ $t['width'] }}px; height: {{ $t['height'] }}px"
         class="absolute flex cursor-pointer select-none flex-col items-center justify-center border-2 px-2 text-center shadow-sm transition-shadow duration-150 hover:shadow-md
                {{ $t['shape'] === 'round' ? 'rounded-full' : 'rounded-2xl' }}
                {{ $statusClass($t['status']) }}
                {{ $isOpen ? 'ring-2 ring-stone-900 ring-offset-2 dark:ring-amber-400 dark:ring-offset-stone-900' : '' }}
                {{ $canEdit ? 'touch-none' : '' }}">
        <span class="max-w-full truncate text-sm font-semibold">{{ $t['name'] }}</span>
        <span class="text-xs tabular-nums opacity-80">{{ $t['used'] }}/{{ $t['seats'] }}</span>
        @if($t['category'] !== 'standard')
            <span class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide opacity-70">{{ $categories[$t['category']] ?? '' }}</span>
        @endif
    </div>
@endforeach

                    @if(empty($tables))
                        <div class="absolute inset-0 flex items-center justify-center p-8 text-center">
                            <div>
                                <p class="font-semibold text-stone-600 dark:text-stone-300">Le plan est vide</p>
                                <p class="mt-1 text-sm text-stone-500">{{ $canEdit ? 'Ajoutez une table ou générez le plan.' : 'Le plan n\'a pas encore été préparé.' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 px-1 text-xs text-stone-500">
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-stone-300"></span>Vide</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>Incomplète</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Complète</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>Dépassée</span>
                <span class="ml-auto">{{ $canEdit ? 'Touchez une table pour gérer ses convives' : 'Mise à jour automatique' }}</span>
            </div>
        </section>

        {{-- Tables et convives non placés --}}
        <aside class="order-2 space-y-5 print:hidden lg:order-none">
            <div class="{{ $card }}">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold">Tables</h2>
                    <span class="text-xs tabular-nums text-stone-500">{{ $totals['tables'] }}</span>
                </div>
                <ul class="mt-3 divide-y divide-stone-100 dark:divide-stone-800">
                    @forelse($tables as $t)
                        <li>
                            <button type="button" wire:click="selectTable({{ $t['id'] }})"
                                    class="flex w-full items-center justify-between gap-3 py-3 text-left text-sm transition hover:text-stone-900 dark:hover:text-white">
                                <span class="flex min-w-0 items-center gap-2.5">
                                    <span class="h-2 w-2 shrink-0 rounded-full {{ $statusDot($t['status']) }}"></span>
                                    <span class="truncate font-medium">{{ $t['name'] }}</span>
                                </span>
                                <span class="shrink-0 tabular-nums text-stone-500">{{ $t['used'] }}/{{ $t['seats'] }}</span>
                            </button>
                        </li>
                    @empty
                        <li class="py-4 text-sm text-stone-500">Aucune table.</li>
                    @endforelse
                </ul>
            </div>

            <div class="{{ $card }}">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold">Non placés</h2>
                    <span class="rounded-full bg-stone-100 px-2 py-0.5 text-xs font-semibold tabular-nums dark:bg-stone-800">{{ count($unseated) }}</span>
                </div>
                <p class="mt-1 text-xs text-stone-500">Touchez une table pour y placer un convive.</p>
                <ul class="mt-3 max-h-72 space-y-2 overflow-y-auto">
                    @forelse($unseated as $g)
                        <li wire:key="unseated-{{ $g['id'] }}" class="flex items-center justify-between gap-3 rounded-xl bg-stone-50 px-3 py-2 text-sm dark:bg-stone-800">
                            <span class="truncate">{{ $g['name'] }}</span>
                            @if($g['seats'] > 1)<span class="shrink-0 text-xs text-stone-500">+{{ $g['seats'] - 1 }}</span>@endif
                        </li>
                    @empty
                        <li class="rounded-xl bg-stone-50 px-3 py-4 text-center text-sm text-stone-500 dark:bg-stone-800">Tous les convives sont placés.</li>
                    @endforelse
                </ul>
            </div>
        </aside>
    </div>

    {{-- Panneau de table --}}
    @if($modal)
        <div class="fixed inset-0 z-[70]" role="dialog" aria-modal="true" aria-labelledby="table-panel-title"
             @keydown.escape.window="$wire.closeModal()" wire:key="panel-{{ $modal['id'] }}">

            <div class="absolute inset-0 bg-stone-950/40 backdrop-blur-[2px]" wire:click="closeModal"></div>

            <div class="absolute inset-x-0 bottom-0 flex max-h-[92dvh] flex-col rounded-t-3xl bg-white shadow-2xl ring-1 ring-stone-900/5
                        md:inset-auto md:left-1/2 md:top-1/2 md:max-h-[86vh] md:w-[34rem] md:-translate-x-1/2 md:-translate-y-1/2 md:rounded-3xl
                        dark:bg-stone-900 dark:ring-white/10"
                 style="padding-bottom: env(safe-area-inset-bottom)">

                <div class="flex justify-center pt-2.5 md:hidden">
                    <span class="h-1.5 w-10 rounded-full bg-stone-300 dark:bg-stone-700"></span>
                </div>

                <header class="flex items-start justify-between gap-4 px-6 pb-4 pt-4 md:pt-6">
                    <div class="min-w-0">
                        <p class="text-xs font-medium uppercase tracking-wide text-stone-500">{{ $categories[$modal['category']] ?? 'Table' }}</p>
                        <h2 id="table-panel-title" class="truncate text-xl font-semibold tracking-tight">{{ $modal['name'] }}</h2>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="h-2 w-40 overflow-hidden rounded-full bg-stone-100 dark:bg-stone-800">
                                <div class="h-full rounded-full transition-all {{ $modal['used'] > $modal['seats'] ? 'bg-red-500' : ($modal['used'] === $modal['seats'] ? 'bg-emerald-500' : 'bg-amber-400') }}"
                                     style="width: {{ $modal['seats'] ? min(100, round($modal['used'] / $modal['seats'] * 100)) : 0 }}%"></div>
                            </div>
                            <span class="text-sm tabular-nums text-stone-600 dark:text-stone-300">{{ $modal['used'] }} / {{ $modal['seats'] }} places</span>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset ring-stone-900/10 {{ $statusClass($modal['status']) }}">{{ $statusLabel($modal['status']) }}</span>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal" aria-label="Fermer"
                            class="-mr-2 shrink-0 rounded-full p-2 text-stone-500 transition hover:bg-stone-100 dark:hover:bg-stone-800">
                        <x-ui-icon name="x" class="h-5 w-5" />
                    </button>
                </header>

                <div class="flex-1 space-y-7 overflow-y-auto px-6 pb-8">

                    @if($modalNotice)
                        <p class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">{{ $modalNotice }}</p>
                    @endif
                    @if($modalError)
                        <p class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-400/10 dark:text-red-300">{{ $modalError }}</p>
                    @endif

                    {{-- Convives à la table --}}
                    <section>
                        <h3 class="mb-2 text-sm font-semibold">Convives</h3>
                        <ul class="divide-y divide-stone-100 dark:divide-stone-800">
                            @forelse($modal['guests'] as $g)
                                <li wire:key="seated-{{ $g['id'] }}" class="flex items-center justify-between gap-3 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium">{{ $g['name'] }}</p>
                                        <p class="mt-0.5 flex items-center gap-2 text-xs text-stone-500">
                                            <span class="rounded-full px-2 py-0.5 font-medium {{ $rsvpBadge($g['status']) }}">{{ $rsvpLabel($g['status']) }}</span>
                                            @if($g['companions'] > 0)<span>+{{ $g['companions'] }} accompagnant(s)</span>@endif
                                        </p>
                                    </div>
                                    @if($canEdit)
                                        <button type="button" wire:click="removeGuest({{ $g['id'] }})"
                                                class="shrink-0 rounded-lg px-3 py-2 text-sm font-medium text-stone-500 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-400/10">
                                            Retirer
                                        </button>
                                    @endif
                                </li>
                            @empty
                                <li class="rounded-2xl bg-stone-50 px-4 py-6 text-center text-sm text-stone-500 dark:bg-stone-800">Aucun convive pour le moment.</li>
                            @endforelse
                        </ul>
                    </section>

                    @if($canEdit)

                        {{-- Ajouter depuis la liste --}}
                        <section>
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-sm font-semibold">Ajouter un convive</h3>
                                <span class="text-xs tabular-nums text-stone-500">{{ $free }} place(s) libre(s)</span>
                            </div>

                            <div class="relative">
                                <x-ui-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" />
                                <input type="search" wire:model.live.debounce.250ms="guestSearch" placeholder="Rechercher un nom"
                                       aria-label="Rechercher un convive"
                                       class="h-11 w-full rounded-xl border border-stone-200 bg-white pl-10 pr-4 text-sm transition focus:border-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-900/10 dark:border-stone-700 dark:bg-stone-800 dark:focus:border-amber-400">
                            </div>

                            <ul class="mt-3 space-y-2">
                                @forelse($candidates as $c)
                                    <li wire:key="candidate-{{ $c['id'] }}">
                                        <button type="button" wire:click="addGuest({{ $c['id'] }})" @disabled(! $c['fits'])
                                                class="group flex w-full items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white px-4 py-3 text-left transition hover:border-stone-900 disabled:cursor-not-allowed disabled:opacity-50 dark:border-stone-700 dark:bg-stone-900 dark:hover:border-amber-400">
                                            <span class="min-w-0">
                                                <span class="block truncate text-sm font-medium">{{ $c['name'] }}</span>
                                                <span class="mt-1 flex items-center gap-2 text-xs text-stone-500">
                                                    <span class="rounded-full px-2 py-0.5 font-medium {{ $rsvpBadge($c['status']) }}">{{ $rsvpLabel($c['status']) }}</span>
                                                    @if($c['seats'] > 1)<span>{{ $c['seats'] }} places</span>@endif
                                                </span>
                                            </span>
                                            <span class="shrink-0 text-xs font-semibold {{ $c['fits'] ? 'text-stone-900 dark:text-amber-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $c['fits'] ? 'Placer' : 'Place insuffisante' }}
                                            </span>
                                        </button>
                                    </li>
                                @empty
                                    <li class="rounded-2xl bg-stone-50 px-4 py-5 text-center text-sm text-stone-500 dark:bg-stone-800">
                                        @if($guestSearch !== '')
                                            Aucun convive ne correspond à « {{ $guestSearch }} ».
                                        @else
                                            Tous les convives sont déjà placés.
                                        @endif
                                    </li>
                                @endforelse
                            </ul>
                        </section>

                        {{-- Convive exceptionnel --}}
                        <section>
                            <button type="button" wire:click="$toggle('showQuick')"
                                    class="flex w-full items-center justify-between rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-left text-sm font-medium transition hover:border-stone-500 dark:border-stone-600">
                                <span>Créer un convive exceptionnel</span>
                                <x-ui-icon name="chevron" class="h-4 w-4 transition {{ $showQuick ? 'rotate-180' : '' }}" />
                            </button>

                            @if($showQuick)
                                <form wire:submit="createGuest" class="mt-3 space-y-3 rounded-2xl bg-stone-50 p-4 dark:bg-stone-800/60">
                                    <div>
                                        <input wire:model="quickName" placeholder="Nom complet" class="{{ $field }}">
                                        @error('quickName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input wire:model="quickPhone" placeholder="Téléphone" class="{{ $field }}">
                                            @error('quickPhone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <select wire:model="quickCompanions" class="{{ $field }}" aria-label="Accompagnants">
                                            @for($i = 0; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i === 0 ? 'Seul(e)' : '+'.$i.' accompagnant(s)' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <p class="text-xs text-stone-500">Ajouté comme confirmé et placé immédiatement.</p>
                                    <button type="submit" class="{{ $btn }} w-full">Ajouter à {{ $modal['name'] }}</button>
                                </form>
                            @endif
                        </section>

                        {{-- Paramètres de la table --}}
                        <section class="border-t border-stone-100 pt-6 dark:border-stone-800">
                            <button type="button" wire:click="$toggle('showSettings')"
                                    class="flex w-full items-center justify-between text-sm font-medium text-stone-600 transition hover:text-stone-900 dark:text-stone-300 dark:hover:text-white">
                                <span>Paramètres de la table</span>
                                <x-ui-icon name="chevron" class="h-4 w-4 transition {{ $showSettings ? 'rotate-180' : '' }}" />
                            </button>

                            @if($showSettings)
                                <form wire:submit="saveTable" class="mt-4 space-y-3">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-stone-600 dark:text-stone-300">Nom</label>
                                        <input wire:model="tableName" class="{{ $field }}">
                                        @error('tableName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-stone-600 dark:text-stone-300">Forme</label>
                                            <select wire:model="tableShape" class="{{ $field }}">
                                                @foreach($shapes as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-stone-600 dark:text-stone-300">Places</label>
                                            <input type="number" min="1" max="30" wire:model="tableSeats" class="{{ $field }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-stone-600 dark:text-stone-300">Catégorie</label>
                                        <select wire:model="tableCategory" class="{{ $field }}">
                                            @foreach($categories as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-center justify-between pt-1">
                                        <button type="submit" class="{{ $btn }}">Enregistrer</button>
                                        <button type="button" wire:click="deleteSelected"
                                                wire:confirm="Supprimer cette table ? Ses convives redeviendront non placés."
                                                class="text-sm font-medium text-red-600 hover:underline dark:text-red-400">
                                            Supprimer la table
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </section>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
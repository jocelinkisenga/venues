@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:ring-2 focus:ring-amber-400 dark:border-slate-700 dark:bg-slate-900';
    $label = 'mb-1 flex items-center justify-between text-sm font-medium';
    $link = 'text-xs font-semibold text-amber-600 hover:underline';
    $panel = 'mt-2 space-y-2 rounded-xl border border-dashed border-amber-400/70 bg-amber-50/60 p-3 dark:bg-amber-900/10';
@endphp

<div class="mx-auto max-w-2xl space-y-5">
    <div>
        <h1 class="text-2xl font-bold">Nouvelle réservation</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Espace, type d'événement et forfait se choisissent ou se créent ici.</p>
    </div>

    @if(session('info'))
        <p class="rounded-xl bg-sky-50 p-3 text-sm text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">{{ session('info') }}</p>
    @endif

    <form wire:submit="save" class="space-y-4 rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">

        <div class="grid gap-4 sm:grid-cols-2">

            {{-- ESPACE --}}
            <div>
                <label class="{{ $label }}">
                    <span>Espace</span>
                    <button type="button" wire:click="$toggle('showSpaceForm')" class="{{ $link }}">
                        {{ $showSpaceForm ? 'Annuler' : '+ Nouvel espace' }}
                    </button>
                </label>
                <select wire:model.live="space_id" class="{{ $field }}">
                    <option value="">Choisir…</option>
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}">{{ $space->name }} ({{ $space->capacity }} pers.)</option>
                    @endforeach
                </select>
                @error('space_id') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                @if($showSpaceForm)
                    <div class="{{ $panel }}">
                        <input wire:model="newSpaceName" placeholder="Nom de l'espace (ex : Jardin)" class="{{ $field }}">
                        @error('newSpaceName') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" min="1" wire:model="newSpaceCapacity" placeholder="Capacité" class="{{ $field }}">
                            <select wire:model="newSpaceZone" class="{{ $field }}">
                                @foreach($zones as $key => $zoneLabel)
                                    <option value="{{ $key }}">{{ $zoneLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" wire:click="addSpace" class="w-full rounded-xl bg-slate-800 py-2 text-sm font-semibold text-white dark:bg-slate-200 dark:text-slate-900">
                            Créer et sélectionner
                        </button>
                    </div>
                @endif
            </div>

            {{-- TYPE D'ÉVÉNEMENT --}}
            <div>
                <label class="{{ $label }}">
                    <span>Type d'événement</span>
                    <button type="button" wire:click="$toggle('showEventTypeForm')" class="{{ $link }}">
                        {{ $showEventTypeForm ? 'Annuler' : '+ Nouveau type' }}
                    </button>
                </label>
                <select wire:model="event_type" class="{{ $field }}">
                    @foreach($eventTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                @error('event_type') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                @if($showEventTypeForm)
                    <div class="{{ $panel }}">
                        <input wire:model="newEventTypeName" placeholder="Ex : Cérémonie de dot, Baptême, Conférence" class="{{ $field }}">
                        @error('newEventTypeName') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        <button type="button" wire:click="addEventType" class="w-full rounded-xl bg-slate-800 py-2 text-sm font-semibold text-white dark:bg-slate-200 dark:text-slate-900">
                            Ajouter et sélectionner
                        </button>
                    </div>
                @endif
            </div>

            {{-- FORFAIT --}}
            <div class="sm:col-span-2">
                <label class="{{ $label }}">
                    <span>Forfait</span>
                    <button type="button" wire:click="$toggle('showPackageForm')" class="{{ $link }}">
                        {{ $showPackageForm ? 'Annuler' : '+ Nouveau forfait' }}
                    </button>
                </label>
                <select wire:model.live="package_id" class="{{ $field }}">
                    <option value="">Sans forfait (location libre)</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }} · {{ $venue->money($package->base_price) }}</option>
                    @endforeach
                </select>
                @error('package_id') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                @if($selectedPackage)
                    <p class="mt-1 text-xs text-slate-500">
                        {{ $selectedPackage->time_type === 'multi_jours' ? $selectedPackage->days.' jour(s)' : 'Soirée / nuit' }}
                        · inclut {{ $selectedPackage->included_hours }} h
                        · heure supplémentaire {{ $venue->money($selectedPackage->overtime_rate) }}
                    </p>
                @endif

                @if($showPackageForm)
                    <div class="{{ $panel }}">
                        <div class="grid gap-2 sm:grid-cols-2">
                            <input wire:model="newPackageName" placeholder="Nom du forfait" class="{{ $field }} sm:col-span-2">
                            @error('newPackageName') <p class="text-xs text-red-500 sm:col-span-2">{{ $message }}</p> @enderror

                            <select wire:model="newPackageType" class="{{ $field }}">
                                <option value="seche">Location sèche</option>
                                <option value="cle_en_main">Clé en main</option>
                            </select>
                            <select wire:model="newPackageTimeType" class="{{ $field }}">
                                <option value="soiree">Soirée / nuit</option>
                                <option value="multi_jours">Multi-jours</option>
                            </select>

                            <input type="number" min="1" max="30" wire:model="newPackageDays" placeholder="Jours" class="{{ $field }}">
                            <input type="number" min="1" max="48" wire:model="newPackageIncludedHours" placeholder="Heures incluses" class="{{ $field }}">

                            <input type="number" min="0" step="1" wire:model="newPackageBasePrice" placeholder="Prix du forfait" class="{{ $field }}">
                            <input type="number" min="0" step="1" wire:model="newPackageOvertimeRate" placeholder="Prix heure supplémentaire" class="{{ $field }}">
                            @error('newPackageBasePrice') <p class="text-xs text-red-500 sm:col-span-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                                Espaces concernés (aucun coché = tous les espaces)
                            </p>
                            <div class="grid gap-1 sm:grid-cols-2">
                                @foreach($spaces as $space)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" wire:model="newPackageSpaceIds" value="{{ $space->id }}" class="rounded">
                                        {{ $space->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" wire:click="addPackage" class="w-full rounded-xl bg-slate-800 py-2 text-sm font-semibold text-white dark:bg-slate-200 dark:text-slate-900">
                            Créer et sélectionner
                        </button>
                    </div>
                @endif
            </div>

            {{-- CLIENT --}}
            <div>
                <label class="{{ $label }}"><span>Client</span></label>
                <input wire:model="client_name" class="{{ $field }}" placeholder="Nom complet">
                @error('client_name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}"><span>Téléphone / WhatsApp</span></label>
                <input wire:model="client_phone" class="{{ $field }}" placeholder="+243 81 234 5678">
                @error('client_phone') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}"><span>Début</span></label>
                <input type="datetime-local" wire:model="starts_at" class="{{ $field }}">
                @error('starts_at') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}"><span>Fin</span></label>
                <input type="datetime-local" wire:model="ends_at" class="{{ $field }}">
                @error('ends_at') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}"><span>Nombre d'invités</span></label>
                <input type="number" min="1" wire:model="guests_count" class="{{ $field }}">
                @error('guests_count') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 self-end pb-2">
                <input id="option" type="checkbox" wire:model="option" class="h-4 w-4 rounded text-amber-500">
                <label for="option" class="text-sm">Bloquer en option ({{ config('venue.option_hold_hours') }} h, sans acompte)</label>
            </div>

            @if($leads->isNotEmpty())
                <div class="sm:col-span-2">
                    <label class="{{ $label }}"><span>Prospect lié (CRM)</span></label>
                    <select wire:model="lead_id" class="{{ $field }}">
                        <option value="">Aucun</option>
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div>
            <label class="{{ $label }}"><span>Notes</span></label>
            <textarea wire:model="notes" rows="3" class="{{ $field }}"></textarea>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="w-full rounded-xl bg-amber-500 py-3 font-semibold text-white shadow hover:bg-amber-600 disabled:opacity-60">
            <span wire:loading.remove>Créer et préparer le devis</span>
            <span wire:loading>Vérification du créneau…</span>
        </button>
    </form>
</div>
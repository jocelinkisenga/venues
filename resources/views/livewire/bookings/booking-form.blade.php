@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:ring-2 focus:ring-amber-400 dark:border-slate-700 dark:bg-slate-900';
    $label = 'mb-1 block text-sm font-medium';
@endphp

<div class="mx-auto max-w-2xl space-y-5">
    <div>
        <h1 class="text-2xl font-bold">Nouvelle réservation</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Vérification anti-conflit automatique, nettoyage inclus.</p>
    </div>

    <form wire:submit="save" class="space-y-4 rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $label }}">Espace</label>
                <select wire:model="space_id" class="{{ $field }}">
                    <option value="">Choisir…</option>
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}">{{ $space->name }} ({{ $space->capacity }} pers.)</option>
                    @endforeach
                </select>
                @error('space_id') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Type d'événement</label>
                <select wire:model="event_type" class="{{ $field }}">
                    @foreach($eventTypes as $type)
                        <option>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $label }}">Forfait</label>
                <select wire:model="package_id" class="{{ $field }}">
                    <option value="">Sans forfait (location libre)</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $label }}">Prospect lié (CRM)</label>
                <select wire:model="lead_id" class="{{ $field }}">
                    <option value="">Aucun</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead->id }}">{{ $lead->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $label }}">Client</label>
                <input wire:model="client_name" class="{{ $field }}" placeholder="Nom complet">
                @error('client_name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Téléphone / WhatsApp</label>
                <input wire:model="client_phone" class="{{ $field }}" placeholder="+225 07 00 00 00 00">
                @error('client_phone') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Début</label>
                <input type="datetime-local" wire:model="starts_at" class="{{ $field }}">
                @error('starts_at') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Fin (ex : jusqu'à l'aube ou dernier jour)</label>
                <input type="datetime-local" wire:model="ends_at" class="{{ $field }}">
                @error('ends_at') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Nombre d'invités</label>
                <input type="number" min="1" wire:model="guests_count" class="{{ $field }}">
                @error('guests_count') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2 self-end pb-2">
                <input id="option" type="checkbox" wire:model="option" class="h-4 w-4 rounded text-amber-500">
                <label for="option" class="text-sm">Bloquer en option ({{ config('venue.option_hold_hours') }} h, sans acompte)</label>
            </div>
        </div>

        <div>
            <label class="{{ $label }}">Notes</label>
            <textarea wire:model="notes" rows="3" class="{{ $field }}"></textarea>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="w-full rounded-xl bg-amber-500 py-3 font-semibold text-white shadow hover:bg-amber-600 disabled:opacity-60">
            <span wire:loading.remove>Créer et préparer le devis</span>
            <span wire:loading>Vérification du créneau…</span>
        </button>
    </form>
</div>

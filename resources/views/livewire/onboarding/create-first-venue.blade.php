@php
    $field = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-400/20 dark:border-slate-700 dark:bg-slate-900';
    $label = 'mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300';
@endphp

<div class="w-full max-w-lg">
    <div class="mb-8 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-lg font-bold text-white shadow-md">
            {{ config('app.name') ? mb_strtoupper(mb_substr(config('app.name'), 0, 1)) : 'S' }}
        </span>
        <h1 class="mt-5 text-2xl font-semibold tracking-tight">Créez votre salle</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Quelques informations suffisent pour commencer. Vous pourrez compléter le reste plus tard.
        </p>
    </div>

    <form wire:submit="create" class="space-y-5 rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
        <div>
            <label for="name" class="{{ $label }}">Nom de la salle</label>
            <input id="name" wire:model="name" placeholder="Ex : Salle Prestige Lubumbashi" class="{{ $field }}" autofocus>
            @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="city" class="{{ $label }}">Ville</label>
            <input id="city" wire:model="city" placeholder="Lubumbashi" class="{{ $field }}">
            @error('city') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="currency" class="{{ $label }}">Devise</label>
                <select id="currency" wire:model="currency" class="{{ $field }}">
                    @foreach($currencies as $code => $meta)
                        <option value="{{ $code }}">{{ $code }} · {{ $meta['label'] }}</option>
                    @endforeach
                </select>
                @error('currency') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="country" class="{{ $label }}">Pays</label>
                <select id="country" wire:model="country" class="{{ $field }}">
                    <option value="CD">République démocratique du Congo</option>
                    <option value="CI">Côte d'Ivoire</option>
                    <option value="SN">Sénégal</option>
                    <option value="CM">Cameroun</option>
                    <option value="KE">Kenya</option>
                    <option value="GN">Guinée</option>
                </select>
                @error('country') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="timezone" class="{{ $label }}">Fuseau horaire</label>
            <input id="timezone" wire:model="timezone" class="{{ $field }}">
            @error('timezone') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus-visible:ring-4 focus-visible:ring-amber-400/40 disabled:opacity-60">
            <span wire:loading.remove>Créer la salle et continuer</span>
            <span wire:loading>Création en cours…</span>
        </button>
    </form>
</div>
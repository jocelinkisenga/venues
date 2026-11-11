@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Données personnelles</h1>
        <p class="text-sm text-slate-500">Retrouvez, exportez ou anonymisez les données liées à un numéro de téléphone.</p>
    </div>

    @if(session('success'))
        <p class="rounded-xl bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('success') }}</p>
    @endif

    <form wire:submit="search" class="{{ $card }} flex flex-wrap gap-3">
        <input wire:model="phone" placeholder="Téléphone du client (+243 …)" class="{{ $field }} flex-1">
        <button class="rounded-xl bg-slate-800 px-4 py-2 font-semibold text-white dark:bg-slate-200 dark:text-slate-900">Rechercher</button>
        @error('phone') <p class="w-full text-sm text-red-500">{{ $message }}</p> @enderror
    </form>

    @if($found)
        <div class="{{ $card }} space-y-4">
            <ul class="grid grid-cols-2 gap-2 text-sm">
                <li>Réservations : <strong>{{ $found['bookings'] }}</strong></li>
                <li>Invités : <strong>{{ $found['guests'] }}</strong></li>
                <li>Prospects : <strong>{{ $found['leads'] }}</strong></li>
                <li>Clients carte : <strong>{{ $found['payers'] }}</strong></li>
            </ul>

            <div class="flex flex-wrap gap-2">
                <button wire:click="export" class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Exporter (JSON)</button>
                <button wire:click="anonymize"
                        wire:confirm="Anonymiser ces données ? Cette action est irréversible. Les paiements et contrats sont conservés."
                        class="rounded-xl bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">
                    Anonymiser
                </button>
            </div>
            <p class="text-xs text-slate-500">L'anonymisation remplace noms et téléphones. Les montants, paiements et contrats restent pour les obligations comptables.</p>
        </div>
    @endif
</div>
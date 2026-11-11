@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Tarifs saisonniers</h1>
        <p class="text-sm text-slate-500">
            Une majoration (ex : 15) ou une réduction (ex : -10) s'applique aux forfaits des événements dont la date de début tombe dans la période.
            Les dates de Ramadan et de Tabaski changent chaque année : saisis-les pour chaque année.
        </p>
    </div>

    <form wire:submit="add" class="{{ $card }} grid gap-3 md:grid-cols-6">
        <input wire:model="name" placeholder="Nom (ex : Tabaski 2027)" class="{{ $field }} md:col-span-2">
        <input type="date" wire:model="starts_on" class="{{ $field }}">
        <input type="date" wire:model="ends_on" class="{{ $field }}">
        <input type="number" step="0.5" wire:model="percent" class="{{ $field }}">
        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Ajouter</button>
        @error('name') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
        @error('ends_on') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
        @error('percent') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
    </form>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr><th class="px-4 py-2">Saison</th><th class="px-4 py-2">Du</th><th class="px-4 py-2">Au</th><th class="px-4 py-2 text-right">Ajustement</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2">{{ $rule->name }}</td>
                        <td class="px-4 py-2">{{ $rule->starts_on->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $rule->ends_on->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-right {{ $rule->percent < 0 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $rule->percent > 0 ? '+' : '' }}{{ rtrim(rtrim($rule->percent, '0'), '.') }} %</td>
                        <td class="px-4 py-2 text-right"><button wire:click="remove({{ $rule->id }})" wire:confirm="Supprimer cette saison ?" class="text-red-500 hover:underline">Supprimer</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-slate-500">Aucune saison définie.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">Dépenses</h1>
            <p class="text-sm text-slate-500">Dépenses générales ou rattachées à un événement.</p>
        </div>
        <div>
            <label class="block text-xs text-slate-500">Mois</label>
            <input type="month" wire:model.live="month" class="{{ $field }}">
        </div>
    </div>

    <form wire:submit="add" class="{{ $card }} grid gap-3 md:grid-cols-6">
        <select wire:model="category" class="{{ $field }}">
            @foreach($categories as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
        </select>
        <input wire:model="label" placeholder="Libellé" class="{{ $field }} md:col-span-2">
        <input wire:model="supplier" placeholder="Fournisseur" class="{{ $field }}">
        <input type="number" min="1" wire:model="amount" placeholder="Montant" class="{{ $field }}">
        <input type="date" wire:model="spent_on" class="{{ $field }}">
        <select wire:model="bookingId" class="{{ $field }} md:col-span-2">
            <option value="">Dépense générale</option>
            @foreach($bookings as $b)
                <option value="{{ $b->id }}">{{ $b->reference }} · {{ $b->client_name }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600 md:col-span-2">Ajouter la dépense</button>
        @error('label') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
        @error('amount') <p class="text-sm text-red-500 md:col-span-6">{{ $message }}</p> @enderror
    </form>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Catégorie</th>
                    <th class="px-4 py-2">Libellé</th>
                    <th class="px-4 py-2">Lié à</th>
                    <th class="px-4 py-2 text-right">Montant</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2 whitespace-nowrap">{{ $e->spent_on->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $categories[$e->category] ?? $e->category }}</td>
                        <td class="px-4 py-2">{{ $e->label }} @if($e->supplier)<span class="text-slate-500">· {{ $e->supplier }}</span>@endif</td>
                        <td class="px-4 py-2 text-slate-500">{{ $e->booking?->reference ?? 'Général' }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($e->amount) }}</td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click="delete({{ $e->id }})" wire:confirm="Supprimer cette dépense ?" class="text-red-500 hover:underline">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-3 text-slate-500">Aucune dépense ce mois-ci.</td></tr>
                @endforelse
            </tbody>
            @if($expenses->isNotEmpty())
                <tfoot>
                    <tr class="border-t border-slate-200 font-semibold dark:border-slate-700">
                        <td class="px-4 py-2" colspan="4">Total du mois</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($total) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
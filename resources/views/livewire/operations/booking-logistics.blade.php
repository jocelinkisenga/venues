@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div>
        <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
        <h1 class="text-2xl font-bold">Logistique · {{ $booking->reference }}</h1>
        <p class="text-sm text-slate-500">Sortie du matériel au départ, retour et casse à la fin de l'événement.</p>
    </div>

    <form wire:submit="checkout" class="{{ $card }} grid gap-3 md:grid-cols-4">
        <select wire:model="itemId" class="{{ $field }} md:col-span-2">
            <option value="">Article à sortir…</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <input type="number" min="1" wire:model="quantity" class="{{ $field }}">
        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Sortir du stock</button>
        @error('itemId') <p class="text-sm text-red-500 md:col-span-4">{{ $message }}</p> @enderror
        @error('quantity') <p class="text-sm text-red-500 md:col-span-4">{{ $message }}</p> @enderror
    </form>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Article</th>
                    <th class="px-4 py-2 text-right">Stock magasin</th>
                    <th class="px-4 py-2 text-right">Sorti pour l'événement</th>
                    <th class="px-4 py-2">Retour</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    @if($row['out'] > 0 || $row['stock'] > 0)
                        <tr class="border-t border-slate-200/70 align-top dark:border-slate-800">
                            <td class="px-4 py-2">{{ $row['item']->name }}</td>
                            <td class="px-4 py-2 text-right">{{ $row['stock'] }}</td>
                            <td class="px-4 py-2 text-right font-semibold">{{ $row['out'] }}</td>
                            <td class="px-4 py-2">
                                @if($row['out'] > 0)
                                    <div class="flex flex-wrap items-center gap-2">
                                        <input type="number" min="0" wire:model="returned.{{ $row['item']->id }}" placeholder="Rendus" class="{{ $field }} w-24">
                                        <input type="number" min="0" wire:model="broken.{{ $row['item']->id }}" placeholder="Cassés" class="{{ $field }} w-24">
                                        <button wire:click="checkin({{ $row['item']->id }})" class="rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700">Valider</button>
                                    </div>
                                    @error('retour-'.$row['item']->id) <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
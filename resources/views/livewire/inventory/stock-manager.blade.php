@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
    $venue = auth()->user()->venue;
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Stock matériel</h1>
        <p class="text-sm text-slate-500">Le stock se calcule à partir des mouvements : achats, sorties, retours et casse.</p>
    </div>

    @if(auth()->user()->role->canSell())
        <div class="grid gap-6 md:grid-cols-2">
            <form wire:submit="addItem" class="{{ $card }} space-y-3">
                <h2 class="font-semibold">Nouvel article</h2>
                <input wire:model="name" placeholder="Nom (ex : Chaise Tiffany)" class="{{ $field }}">
                @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                <select wire:model="category" class="{{ $field }}">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <input type="number" step="1" wire:model="replacement_cost" placeholder="Coût de remplacement unitaire" class="{{ $field }}">
                <button class="w-full rounded-xl bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600">Ajouter l'article</button>
            </form>

            <form wire:submit="adjust" class="{{ $card }} space-y-3">
                <h2 class="font-semibold">Entrée ou correction de stock</h2>
                <select wire:model="itemId" class="{{ $field }}">
                    <option value="">Choisir un article…</option>
                    @foreach($rows as $row)
                        <option value="{{ $row['item']->id }}">{{ $row['item']->name }}</option>
                    @endforeach
                </select>
                <select wire:model="movementType" class="{{ $field }}">
                    <option value="achat">Achat (entrée)</option>
                    <option value="ajustement">Ajustement (+ ou −)</option>
                </select>
                <input type="number" wire:model="quantity" class="{{ $field }}">
                @error('quantity') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                <input wire:model="note" placeholder="Note (facture, inventaire…)" class="{{ $field }}">
                <button class="w-full rounded-xl bg-slate-800 py-2 font-semibold text-white dark:bg-slate-200 dark:text-slate-900">Enregistrer</button>
            </form>
        </div>
    @endif

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Article</th>
                    <th class="px-4 py-2">Catégorie</th>
                    <th class="px-4 py-2 text-right">En stock</th>
                    <th class="px-4 py-2 text-right">Valeur de remplacement</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2">{{ $row['item']->name }}</td>
                        <td class="px-4 py-2 text-slate-500">{{ $categories[$row['item']->category] ?? $row['item']->category }}</td>
                        <td class="px-4 py-2 text-right {{ $row['stock'] < 0 ? 'text-red-600' : '' }}">{{ $row['stock'] }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($row['stock'] * $row['item']->replacement_cost) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-3 text-slate-500">Aucun article. Ajoutez le premier.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
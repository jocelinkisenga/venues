@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
            <h1 class="text-2xl font-bold">Invités · {{ $booking->reference }}</h1>
            <p class="text-sm text-slate-500">Capacité : {{ $capacity }} · Confirmés (avec accompagnants) : <strong>{{ $confirmed }}</strong> · Refus : {{ $declined }} · Sans réponse : {{ $pending }}</p>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <form wire:submit="add" class="{{ $card }} space-y-3">
            <h2 class="font-semibold">Ajouter un invité</h2>
            <input wire:model="newName" placeholder="Nom complet" class="{{ $field }} w-full">
            @error('newName') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <input wire:model="newPhone" placeholder="WhatsApp (optionnel)" class="{{ $field }} w-full">
            <button class="w-full rounded-xl bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600">Ajouter</button>
        </form>

        <form wire:submit="import" class="{{ $card }} space-y-3">
            <h2 class="font-semibold">Import rapide</h2>
            <p class="text-xs text-slate-500">Une ligne par invité : <code>Nom ; téléphone</code> (500 lignes maximum).</p>
            <textarea wire:model="bulk" rows="5" placeholder="Jean Kabila ; 0812345678" class="{{ $field }} w-full font-mono text-sm"></textarea>
            <button class="w-full rounded-xl bg-slate-800 py-2 font-semibold text-white dark:bg-slate-200 dark:text-slate-900">Importer</button>
        </form>
    </div>

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Invité</th>
                    <th class="px-4 py-2">Réponse</th>
                    <th class="px-4 py-2">Accompagnants</th>
                    <th class="px-4 py-2">Relance</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    @php $g = $row['guest']; @endphp
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2">{{ $g->full_name }} @if($g->phone)<span class="text-xs text-slate-500">· {{ $g->phone }}</span>@endif</td>
                        <td class="px-4 py-2">
                            <span class="{{ ['invited' => 'text-amber-600', 'confirmed' => 'text-emerald-600', 'declined' => 'text-slate-500'][$g->rsvp_status] }}">
                                {{ ['invited' => 'En attente', 'confirmed' => 'Présent', 'declined' => 'Absent'][$g->rsvp_status] }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $g->companions }}</td>
                        <td class="px-4 py-2">
                            @if($row['whatsapp'])
                                <a href="{{ $row['whatsapp'] }}" target="_blank" class="text-emerald-600 hover:underline">WhatsApp</a>
                            @else
                                <span class="text-xs text-slate-400">Pas de numéro</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <button wire:click="remove({{ $g->id }})" wire:confirm="Retirer cet invité ?" class="text-red-500 hover:underline">Retirer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-slate-500">Aucun invité. Ajoutez-les ou importez la liste.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
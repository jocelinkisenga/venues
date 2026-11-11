@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $card = 'rounded-2xl border border-white/40 bg-white/70 p-5 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60';
    $t = $report['totals'];
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">Rentabilité</h1>
            <p class="text-sm text-slate-500">Marge = facturé − commissions − dépenses − personnel.</p>
        </div>
        <div class="flex gap-2">
            <div>
                <label class="block text-xs text-slate-500">Du</label>
                <input type="date" wire:model.live="from" class="{{ $field }}">
            </div>
            <div>
                <label class="block text-xs text-slate-500">Au</label>
                <input type="date" wire:model.live="to" class="{{ $field }}">
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Facturé</p><p class="text-xl font-bold">{{ $venue->money($t['invoiced']) }}</p></div>
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Dépenses</p><p class="text-xl font-bold">{{ $venue->money($t['expenses']) }}</p></div>
        <div class="{{ $card }}"><p class="text-xs text-slate-500">Personnel + commissions</p><p class="text-xl font-bold">{{ $venue->money($t['staff'] + $t['commissions']) }}</p></div>
        <div class="{{ $card }}">
            <p class="text-xs text-slate-500">Marge</p>
            <p class="text-xl font-bold {{ $t['margin'] < 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $venue->money($t['margin']) }}</p>
        </div>
    </div>

    @if($report['byCategory']->isNotEmpty())
        <div class="{{ $card }}">
            <h2 class="mb-3 font-semibold">Dépenses par catégorie</h2>
            <div class="grid gap-2 md:grid-cols-3 text-sm">
                @foreach($report['byCategory'] as $category => $total)
                    <div class="flex justify-between border-b border-slate-200/70 py-1 dark:border-slate-800">
                        <span>{{ $category }}</span><span>{{ $venue->money($total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="{{ $card }} overflow-x-auto p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-100/80 text-left text-xs uppercase text-slate-500 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2">Événement</th>
                    <th class="px-4 py-2 text-right">Facturé</th>
                    <th class="px-4 py-2 text-right">Encaissé</th>
                    <th class="px-4 py-2 text-right">Dépenses</th>
                    <th class="px-4 py-2 text-right">Personnel</th>
                    <th class="px-4 py-2 text-right">Marge</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report['rows'] as $row)
                    <tr class="border-t border-slate-200/70 dark:border-slate-800">
                        <td class="px-4 py-2">
                            <a href="{{ route('bookings.quote', $row['booking']) }}" class="hover:underline">{{ $row['booking']->reference }}</a>
                            <span class="text-slate-500">· {{ $row['booking']->event_type }}</span>
                        </td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($row['invoiced']) }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($row['collected']) }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($row['expenses']) }}</td>
                        <td class="px-4 py-2 text-right">{{ $venue->money($row['staff']) }}</td>
                        <td class="px-4 py-2 text-right font-semibold {{ $row['margin'] < 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $venue->money($row['margin']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-3 text-slate-500">Aucun événement sur cette période.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="text-xs text-slate-500">
        Dépenses générales non rattachées à un événement : {{ $venue->money($t['general_expenses']) }}, déjà incluses dans la marge de la période.
    </p>
</div>
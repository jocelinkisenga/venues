@php
    $field = 'rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
@endphp

<div class="space-y-5">
    <div>
        <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
        <h1 class="text-2xl font-bold">Plan de salle · {{ $booking->reference }}</h1>
        <p class="text-sm text-slate-500">{{ $booking->guests_count }} invités · {{ $seated }} places installées
            @if($missing > 0)<span class="text-red-500">· {{ $missing }} places manquantes</span>@endif
        </p>
    </div>

    <form wire:submit="generate" class="flex flex-wrap items-end gap-3 rounded-2xl border border-white/40 bg-white/70 p-4 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <div>
            <label class="block text-xs text-slate-500">Disposition</label>
            <select wire:model="tableLayout" class="{{ $field }}">
                @foreach($layouts as $key => $layout)
                    <option value="{{ $key }}">{{ $layout['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-slate-500">Places par table</label>
            <input type="number" min="1" max="20" wire:model="seatsPerTable" class="{{ $field }} w-24">
        </div>
        <button class="rounded-xl bg-amber-500 px-4 py-2 font-semibold text-white hover:bg-amber-600">Générer les tables</button>
    </form>

    <div class="relative h-[480px] overflow-auto rounded-2xl border border-white/40 bg-slate-100/80 p-4 dark:border-slate-800 dark:bg-slate-900">
        @forelse($tables as $table)
            <button wire:click="toggleVip({{ $table->id }})"
                    style="left: {{ $table->x }}px; top: {{ $table->y }}px"
                    class="absolute flex flex-col items-center justify-center text-xs font-semibold shadow
                           {{ $table->shape === 'round' ? 'h-24 w-24 rounded-full' : 'h-16 w-32 rounded-lg' }}
                           {{ $table->is_vip ? 'bg-amber-400 text-slate-900' : 'bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                <span>{{ $table->label }}{{ $table->is_vip ? ' ★' : '' }}</span>
                <span class="font-normal">{{ $table->seats }} pers.</span>
            </button>
        @empty
            <p class="text-sm text-slate-500">Aucune table. Choisissez une disposition puis générez.</p>
        @endforelse
    </div>
    <p class="text-xs text-slate-500">Cliquez sur une table pour la marquer VIP (mariés, famille, officiels).</p>
</div>

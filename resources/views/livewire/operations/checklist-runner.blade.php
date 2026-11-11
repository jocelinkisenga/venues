<div class="mx-auto max-w-2xl space-y-5">
    @php
        $done = collect($checklist->items)->where('done', true)->count();
        $total = count($checklist->items);
    @endphp

    <div>
        <a href="{{ route('bookings.beo', $booking) }}" class="text-sm text-slate-500 hover:underline">← BEO</a>
        <h1 class="text-2xl font-bold">{{ $phase === 'entry' ? 'Checklist d\'entrée' : 'Checklist de sortie' }}</h1>
        <p class="text-sm text-slate-500">{{ $booking->reference }} · {{ $done }}/{{ $total }} validés</p>
        <div class="mt-2 h-2 rounded-full bg-slate-200 dark:bg-slate-800">
            <div class="h-2 rounded-full bg-emerald-500 transition-all" style="width: {{ $total ? $done / $total * 100 : 0 }}%"></div>
        </div>
    </div>

    <ul class="space-y-2">
        @foreach($checklist->items as $index => $item)
            <li>
                <button wire:click="toggle({{ $index }})" class="flex w-full items-center gap-3 rounded-xl border border-white/40 bg-white/70 p-4 text-left backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 {{ $item['done'] ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300' }}">
                        {{ $item['done'] ? '✓' : '' }}
                    </span>
                    <span class="{{ $item['done'] ? 'text-slate-400 line-through' : '' }}">{{ $item['label'] }}</span>
                </button>
            </li>
        @endforeach
    </ul>

    @if($checklist->isComplete())
        <p class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Checklist complète, enregistrée le {{ $checklist->completed_at?->format('d/m/Y H:i') }}.</p>
    @endif
</div>

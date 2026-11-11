@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $venue = $booking->venue;
@endphp

<div class="mx-auto max-w-xl space-y-5">
    <div>
        <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
        <h1 class="text-2xl font-bold">Caution · {{ $booking->reference }}</h1>
        <p class="text-sm text-slate-500">Dépôt de garantie, distinct des paiements et restitué après l'état des lieux de sortie.</p>
    </div>

    @if(! $held)
        <form wire:submit="hold" class="space-y-3 rounded-2xl border border-white/40 bg-white/70 p-5 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
            <label class="block text-sm font-medium">Montant de la caution</label>
            <input type="number" min="1" wire:model="amount" class="{{ $field }}">
            @error('amount') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <button class="w-full rounded-xl bg-amber-500 py-2.5 font-semibold text-white hover:bg-amber-600">Enregistrer la caution reçue</button>
        </form>
    @else
        <form wire:submit="settle" class="space-y-3 rounded-2xl border border-white/40 bg-white/70 p-5 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
            <p class="text-sm">Caution en cours : <strong>{{ $venue->money($held->amount) }}</strong></p>
            <label class="block text-sm font-medium">Montant retenu pour dégâts (0 = restitution intégrale)</label>
            <input type="number" min="0" wire:model="retained" class="{{ $field }}">
            @error('retained') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <label class="block text-sm font-medium">Motif de la retenue</label>
            <input wire:model="reason" class="{{ $field }}">
            @error('reason') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            <button class="w-full rounded-xl bg-slate-800 py-2.5 font-semibold text-white dark:bg-slate-200 dark:text-slate-900">Régler la caution</button>
        </form>
    @endif

    @if($deposits->isNotEmpty())
        <div class="rounded-2xl border border-white/40 bg-white/70 p-5 text-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
            <h2 class="mb-2 font-semibold">Historique</h2>
            @foreach($deposits as $deposit)
                <p class="border-t border-slate-200/70 py-2 first:border-0 dark:border-slate-800">
                    {{ $venue->money($deposit->amount) }} · {{ $deposit->status }}
                    @if($deposit->status === 'retained') · retenu {{ $venue->money($deposit->retained_amount) }} ({{ $deposit->reason }}) @endif
                </p>
            @endforeach
        </div>
    @endif
</div>
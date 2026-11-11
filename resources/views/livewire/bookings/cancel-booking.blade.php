@php
    $field = 'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900';
    $venue = $booking->venue;
@endphp

<div class="mx-auto max-w-xl space-y-5">
    <div>
        <a href="{{ route('bookings.quote', $booking) }}" class="text-sm text-slate-500 hover:underline">← Devis</a>
        <h1 class="text-2xl font-bold">Annuler {{ $booking->reference }}</h1>
    </div>

    <div class="space-y-2 rounded-2xl border border-white/40 bg-white/70 p-5 text-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <p>Jours avant l'événement : <strong>{{ $preview['days'] }}</strong></p>
        <p>Taux de remboursement selon la politique : <strong>{{ $preview['percent'] }} %</strong></p>
        <p>Montant encaissé : <strong>{{ $venue->money($preview['paid']) }}</strong></p>
        <p>Remboursement prévu : <strong class="text-red-600">{{ $venue->money($preview['refund']) }}</strong></p>
    </div>

    <form wire:submit="cancel" class="space-y-4 rounded-2xl border border-white/40 bg-white/70 p-5 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/60">
        <div>
            <label class="mb-1 block text-sm font-medium">Traitement du montant</label>
            <select wire:model="mode" class="{{ $field }}">
                <option value="refund">Remboursement (Stripe ou sortie manuelle)</option>
                <option value="credit_note">Avoir (crédit sur une future réservation)</option>
                <option value="none">Aucun remboursement</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Motif (obligatoire)</label>
            <input wire:model="reason" class="{{ $field }}">
            @error('reason') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </div>
        <button type="submit" wire:confirm="Confirmer l'annulation de cette réservation ?"
                class="w-full rounded-xl bg-red-600 py-3 font-semibold text-white hover:bg-red-700">
            Confirmer l'annulation
        </button>
    </form>
</div>
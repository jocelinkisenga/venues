<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Votre réservation · {{ $booking->venue->name }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-amber-50 p-4 text-slate-800">
    @php $venue = $booking->venue; @endphp

    <div class="mx-auto max-w-xl space-y-5">
        <div>
            <h1 class="text-2xl font-bold">{{ $venue->name }}</h1>
            <p class="text-sm text-slate-500">Réservation {{ $booking->reference }}</p>
        </div>

        <div class="space-y-1 rounded-2xl border border-white/50 bg-white/80 p-5 text-sm shadow-sm">
            <p><strong>{{ $booking->event_type }}</strong> · {{ $booking->space->name }}</p>
            <p>{{ $booking->starts_at->format('d/m/Y H:i') }} → {{ $booking->ends_at->format('d/m/Y H:i') }}</p>
            <p>{{ $booking->guests_count }} invités</p>
        </div>

        @if($booking->quote)
            <div class="space-y-2 rounded-2xl border border-white/50 bg-white/80 p-5 text-sm shadow-sm">
                @foreach($booking->quote->lines as $line)
                    <div class="flex justify-between">
                        <span>{{ $line->label }}</span>
                        <span>{{ $venue->money($line->line_total) }}</span>
                    </div>
                @endforeach
                @if($booking->quote->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600">
                        <span>Remise</span><span>- {{ $venue->money($booking->quote->discount_amount) }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>TVA</span><span>{{ $venue->money($booking->quote->tax_amount) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold">
                    <span>Total</span><span>{{ $venue->money($total) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Déjà payé</span><span>{{ $venue->money($paid) }}</span>
                </div>
                <div class="flex justify-between font-semibold">
                    <span>Solde restant</span><span>{{ $venue->money(max(0, $total - $paid)) }}</span>
                </div>
            </div>
        @endif

        @if($booking->status->value === 'option' && $paid < $deposit)
            <form method="POST" action="{{ $payUrl }}" class="space-y-2">
                @csrf
                <input type="hidden" name="kind" value="deposit">
                <button class="w-full rounded-xl bg-amber-500 py-3 font-semibold text-white shadow hover:bg-amber-600">
                    Payer l'acompte · {{ $venue->money($deposit - $paid) }}
                </button>
            </form>
        @elseif($total - $paid > 0)
            <form method="POST" action="{{ $payUrl }}" class="space-y-2">
                @csrf
                <input type="hidden" name="kind" value="balance">
                <button class="w-full rounded-xl bg-emerald-600 py-3 font-semibold text-white shadow hover:bg-emerald-700">
                    Régler le solde · {{ $venue->money($total - $paid) }}
                </button>
            </form>
        @endif

        <p class="text-center text-xs text-slate-500">
            Paiement sécurisé par carte. Pour Mobile Money ou virement, contactez la salle.
        </p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
    h1 { font-size: 18px; margin: 0; }
    h2 { font-size: 14px; margin: 16px 0 6px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th, td { border-bottom: 1px solid #e2e8f0; padding: 5px; text-align: left; }
    th { background: #f1f5f9; font-size: 10px; text-transform: uppercase; }
    .right { text-align: right; }
    .muted { color: #64748b; }
    .totals td { border: none; padding: 3px 5px; }
    .grand { font-size: 13px; font-weight: bold; }
</style>
</head>
<body>
    <h1>{{ $venue->name }}</h1>
    <p class="muted">{{ $venue->city }} @if($venue->phone)· Tél. {{ $venue->phone }}@endif</p>

    <h2>Devis {{ $booking->reference }}</h2>
    <p>
        <strong>Client :</strong> {{ $booking->client_name }} ({{ $booking->client_phone }})<br>
        <strong>Événement :</strong> {{ $booking->event_type }} · {{ $booking->space->name }}<br>
        <strong>Période :</strong> {{ $booking->starts_at->format('d/m/Y H:i') }} → {{ $booking->ends_at->format('d/m/Y H:i') }}<br>
        <strong>Invités :</strong> {{ $booking->guests_count }}
    </p>

    <table>
        <thead>
            <tr><th>Prestation</th><th>Partenaire</th><th class="right">Qté</th><th class="right">PU</th><th class="right">Total</th></tr>
        </thead>
        <tbody>
            @foreach($quote->lines as $line)
                <tr>
                    <td>{{ $line->label }}</td>
                    <td>{{ $line->partner?->name ?? '—' }}</td>
                    <td class="right">{{ rtrim(rtrim($line->quantity, '0'), '.') }}</td>
                    <td class="right">{{ $venue->money($line->unit_price) }}</td>
                    <td class="right">{{ $venue->money($line->line_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="width: 45%; margin-left: auto; margin-top: 12px;">
        <tr><td>Sous-total</td><td class="right">{{ $venue->money($quote->subtotal) }}</td></tr>
        @if($quote->discount_amount > 0)
            <tr><td>Remise ({{ $quote->discount_reason }})</td><td class="right">- {{ $venue->money($quote->discount_amount) }}</td></tr>
        @endif
        <tr><td>TVA ({{ rtrim(rtrim($venue->tax_rate, '0'), '.') }} %)</td><td class="right">{{ $venue->money($quote->tax_amount) }}</td></tr>
        <tr class="grand"><td>Total</td><td class="right">{{ $venue->money($quote->total) }}</td></tr>
        <tr><td>Acompte ({{ $venue->deposit_percent }} %)</td><td class="right">{{ $venue->money($quote->total * $venue->deposit_percent / 100) }}</td></tr>
        <tr><td>Déjà encaissé</td><td class="right">{{ $venue->money($paid) }}</td></tr>
        <tr class="grand"><td>Solde restant</td><td class="right">{{ $venue->money(max(0, $quote->total - $paid)) }}</td></tr>
    </table>

    <h2>Conditions</h2>
    <p class="muted">
        Acompte de {{ $venue->deposit_percent }} % à la signature, solde avant l'événement.
        Modes de paiement : Mobile Money, carte, virement, espèces.
    </p>
</body>
</html>

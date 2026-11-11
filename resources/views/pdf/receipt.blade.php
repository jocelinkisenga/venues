<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
    h1 { font-size: 16px; margin: 0; }
    .amount { font-size: 18px; font-weight: bold; margin: 16px 0; }
    .muted { color: #64748b; }
</style>
</head>
<body>
    <h1>{{ $venue->name }}</h1>
    <p class="muted">Reçu de paiement n° {{ $payment->id }}</p>
    <p>
        Reçu de : <strong>{{ $booking->client_name }}</strong><br>
        Réservation : {{ $booking->reference }} · {{ $booking->event_type }}<br>
        Date : {{ $payment->paid_at->format('d/m/Y H:i') }}<br>
        Motif : {{ $payment->kind === 'deposit' ? 'Acompte' : ($payment->kind === 'balance' ? 'Solde' : 'Autre') }}<br>
        Mode : {{ config("venue.payment_methods.{$payment->method}") }}
        @if($payment->reference)<br>Référence transaction : {{ $payment->reference }}@endif
    </p>
    <p class="amount">{{ $venue->money($payment->amount) }}</p>
    <p class="muted">Document généré électroniquement.</p>
</body>
</html>

<?php

namespace App\Listeners\Stripe;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\CardPaymentService;
use App\Services\PaymentReconciler;
use Laravel\Cashier\Events\WebhookReceived;

class RecordCheckoutPayment
{
    public function __construct(
        private PaymentReconciler $reconciler,
        private CardPaymentService $cards,
    ) {}

    public function handle(WebhookReceived $event): void
    {
        if (($event->payload['type'] ?? null) !== 'checkout.session.completed') {
            return;
        }

        $session = $event->payload['data']['object'];

        if (($session['payment_status'] ?? null) !== 'paid') {
            return;
        }

        $meta = $session['metadata'] ?? [];
        $booking = Booking::with('venue')->find($meta['booking_id'] ?? null);

        if (! $booking) {
            return;
        }

        // Idempotence : un webhook reçu deux fois ne crée pas de doublon
        Payment::firstOrCreate(
            ['reference' => $session['payment_intent']],
            [
                'booking_id' => $booking->id,
                'payer_id' => $meta['payer_id'] ?? null,
                'amount' => CardPaymentService::fromMinor((int) $session['amount_total'], $booking->venue->currency),
                'kind' => $meta['kind'] ?? 'other',
                'method' => 'card',
                'provider' => 'stripe',
                'status' => 'succeeded',
                'paid_at' => now(),
            ],
        );

        $this->reconciler->reconcile($booking);
    }
}
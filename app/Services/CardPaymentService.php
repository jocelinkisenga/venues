<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payer;
use App\Models\Payment;

class CardPaymentService
{
    /** Retrouve ou crée le client payeur à partir de son téléphone (normalisé en chiffres). */
    public function payerFor(Booking $booking): Payer
    {
        $phone = preg_replace('/\D/', '', $booking->client_phone);

        return Payer::firstOrCreate(
            ['venue_id' => $booking->venue_id, 'phone' => $phone],
            ['name' => $booking->client_name],
        );
    }

    /**
     * Ouvre une session Stripe Checkout pour un montant en unités principales.
     * Cashier crée le client Stripe si nécessaire.
     */
    public function checkoutUrl(Booking $booking, float $amount, string $kind, string $label): string
    {
        $payer = $this->payerFor($booking);

        $metadata = [
            'booking_id' => (string) $booking->id,
            'payer_id' => (string) $payer->id,
            'kind' => $kind,
        ];

        $checkout = $payer->checkoutCharge(
            self::toMinor($amount, $booking->venue->currency),
            "{$label} · {$booking->reference}",
            1,
            [
                'metadata' => $metadata,
                'payment_intent_data' => ['metadata' => $metadata],
                'success_url' => url('/paiement/merci'),
                'cancel_url' => url('/paiement/annule'),
            ],
        );

        return $checkout->asStripeCheckoutSession()->url;
    }

    /** Rembourse le PaymentIntent d'un paiement carte, en unités principales. */
    public function refund(Payment $payment, float $amount): void
    {
        $payer = $payment->payer ?? $this->payerFor($payment->booking);

        $payer->refund($payment->reference, [
            'amount' => self::toMinor($amount, $payment->booking->venue->currency),
        ]);
    }

    public static function toMinor(float $amount, string $currency): int
    {
        return (int) round($amount * 10 ** config("venue.currencies.{$currency}.decimals", 0));
    }

    public static function fromMinor(int $amount, string $currency): float
    {
        return $amount / 10 ** config("venue.currencies.{$currency}.decimals", 0);
    }
}
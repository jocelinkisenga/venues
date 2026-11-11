<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\CreditNote;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CancellationService
{
    public function __construct(
        private CardPaymentService $cards,
        private DocumentNumberer $numberer,
    ) {}

    public function preview(Booking $booking): array
    {
        $booking->loadMissing('venue');

        $paid = (float) $booking->payments()->sum('amount');
        $days = (int) floor(now()->floatDiffInDays($booking->starts_at, false));

        $percent = 0;
        foreach (config('venue.cancellation_policy') as $rule) {
            if ($days >= $rule['days']) {
                $percent = $rule['refund'];
                break;
            }
        }

        return [
            'days' => $days,
            'percent' => $percent,
            'paid' => $paid,
            'refund' => round($paid * $percent / 100),
        ];
    }

    /** @param string $mode refund | credit_note | none */
    public function cancel(Booking $booking, User $actor, string $reason, string $mode): Cancellation
    {
        if (in_array($booking->status, [
            BookingStatus::Cancelled, BookingStatus::Finished, BookingStatus::InProgress, BookingStatus::Cleaning,
        ], true)) {
            throw new \DomainException('Cette réservation ne peut plus être annulée.');
        }

        $preview = $this->preview($booking);
        $refund = $mode === 'none' ? 0 : $preview['refund'];

        return DB::transaction(function () use ($booking, $actor, $reason, $mode, $preview, $refund) {
            $cancellation = Cancellation::create([
                'booking_id' => $booking->id,
                'cancelled_by' => $actor->id,
                'reason' => $reason,
                'refund_percent' => $preview['percent'],
                'paid_amount' => $preview['paid'],
                'refund_amount' => $refund,
                'mode' => $mode,
            ]);

            if ($refund > 0 && $mode === 'refund') {
                $this->refund($booking, $refund, $actor);
            }

            if ($refund > 0 && $mode === 'credit_note') {
                CreditNote::create([
                    'venue_id' => $booking->venue_id,
                    'booking_id' => $booking->id,
                    'number' => $this->numberer->next($booking->venue, 'AVO'),
                    'amount' => $refund,
                    'reason' => $reason,
                ]);
            }

            $booking->update(['status' => BookingStatus::Cancelled, 'option_expires_at' => null]);

            return $cancellation;
        });
    }

    private function refund(Booking $booking, float $amount, User $actor): void
    {
        $remaining = $amount;

        // Carte : Stripe, via Cashier, par ordre chronologique
        $cardPayments = $booking->payments()
            ->where('provider', 'stripe')
            ->where('kind', '!=', 'refund')
            ->orderBy('id')
            ->get();

        foreach ($cardPayments as $payment) {
            if ($remaining <= 0) {
                break;
            }

            $chunk = min($remaining, (float) $payment->amount);
            $this->cards->refund($payment, $chunk);
            $this->recordRefund($booking, $actor, $chunk, 'card', 'stripe', 'succeeded', 'rf-'.Str::random(12));
            $remaining -= $chunk;
        }

        // Reste : sortie hors ligne (Mobile Money, virement, espèces), à valider manuellement
        if ($remaining > 0) {
            $this->recordRefund($booking, $actor, $remaining, 'bank_transfer', null, 'pending', null);
        }
    }

    private function recordRefund(
        Booking $booking,
        User $actor,
        float $amount,
        string $method,
        ?string $provider,
        string $status,
        ?string $reference,
    ): void {
        Payment::create([
            'booking_id' => $booking->id,
            'recorded_by' => $actor->id,
            'amount' => -round($amount, 2),
            'kind' => 'refund',
            'method' => $method,
            'provider' => $provider,
            'status' => $status,
            'reference' => $reference,
            'paid_at' => now(),
        ]);
    }
}
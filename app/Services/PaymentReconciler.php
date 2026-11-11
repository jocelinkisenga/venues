<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;

/** Confirme l'option dès que l'acompte est encaissé, quelle que soit la source du paiement. */
class PaymentReconciler
{
    public function reconcile(Booking $booking): void
    {
        $booking->loadMissing(['quote', 'venue']);

        if (! $booking->quote) {
            return;
        }

        $paid = (float) $booking->payments()->sum('amount');
        $due = round((float) $booking->quote->total * $booking->venue->deposit_percent / 100);

        if ($booking->status === BookingStatus::Option && $paid >= $due) {
            $booking->update(['status' => BookingStatus::Confirmed, 'option_expires_at' => null]);
        }
    }
}
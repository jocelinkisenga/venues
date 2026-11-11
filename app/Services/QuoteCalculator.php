<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Quote;

class QuoteCalculator
{
    /** Crée le devis avec les lignes du forfait (base + heures supplémentaires). */
    public function createForBooking(Booking $booking): Quote
    {
        $quote = Quote::firstOrCreate(['booking_id' => $booking->id]);

        if ($booking->package && $quote->lines()->doesntExist()) {
            $package = $booking->package;

            $quote->lines()->create([
                'label' => "Forfait {$package->name}",
                'quantity' => 1,
                'unit_price' => $package->base_price,
            ]);

            $hours = (int) $booking->starts_at->diffInHours($booking->ends_at);
            $extra = max(0, $hours - $package->included_hours);

            if ($extra > 0 && $package->overtime_rate > 0) {
                $quote->lines()->create([
                    'label' => 'Heures supplémentaires',
                    'quantity' => $extra,
                    'unit_price' => $package->overtime_rate,
                ]);
            }
        }

        return $this->recalculate($quote);
    }

    /** Recalcule lignes, commissions partenaires, remise, TVA et total. */
    public function recalculate(Quote $quote): Quote
    {
        $quote->loadMissing(['booking.venue', 'lines.partner']);

        $subtotal = 0.0;
        $commission = 0.0;

        foreach ($quote->lines as $line) {
            $lineTotal = round((float) $line->quantity * (float) $line->unit_price, 2);
            $percent = (float) ($line->partner?->commission_percent ?? $line->commission_percent);
            $lineCommission = round($lineTotal * $percent / 100, 2);

            $line->update([
                'line_total' => $lineTotal,
                'commission_percent' => $percent,
                'commission_amount' => $lineCommission,
            ]);

            $subtotal += $lineTotal;
            $commission += $lineCommission;
        }

        $discount = match ($quote->discount_type) {
            'percent' => round($subtotal * (float) $quote->discount_value / 100, 2),
            'fixed' => min((float) $quote->discount_value, $subtotal),
            default => 0.0,
        };

        $taxable = $subtotal - $discount;
        $tax = round($taxable * (float) $quote->booking->venue->tax_rate / 100, 2);

        $quote->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'commission_total' => $commission,
            'tax_amount' => $tax,
            'total' => $taxable + $tax,
        ]);

        return $quote->refresh();
    }
}

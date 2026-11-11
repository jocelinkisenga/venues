<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Assignment;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Venue;
use Carbon\Carbon;

/**
 * Marge = montant facturé − commissions partenaires − dépenses − personnel.
 * Base simplifiée : les dépenses sont rattachées à la date de dépense, les événements à leur date de début.
 */
class ProfitabilityService
{
    public function forBooking(Booking $booking): array
    {
        $booking->loadMissing('quote');

        $invoiced = (float) ($booking->quote?->total ?? 0);
        $commissions = (float) ($booking->quote?->commission_total ?? 0);
        $expenses = (float) Expense::where('booking_id', $booking->id)->sum('amount');
        $staff = (float) Assignment::where('booking_id', $booking->id)->sum('pay_amount');

        return [
            'invoiced' => $invoiced,
            'collected' => (float) $booking->payments()->sum('amount'),
            'commissions' => $commissions,
            'expenses' => $expenses,
            'staff' => $staff,
            'margin' => $invoiced - $commissions - $expenses - $staff,
        ];
    }

    public function period(Venue $venue, Carbon $from, Carbon $to): array
    {
        $bookings = Booking::where('venue_id', $venue->id)
            ->where('status', '!=', BookingStatus::Cancelled->value)
            ->whereBetween('starts_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->with('quote')
            ->get();

        $rows = $bookings->map(fn (Booking $b) => ['booking' => $b] + $this->forBooking($b))->values();

        $expenses = Expense::where('venue_id', $venue->id)
            ->whereBetween('spent_on', [$from->toDateString(), $to->toDateString()]);

        $totalExpenses = (float) (clone $expenses)->sum('amount');
        $generalExpenses = (float) (clone $expenses)->whereNull('booking_id')->sum('amount');
        $byCategory = (clone $expenses)->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->pluck('total', 'category');

        $invoiced = (float) $rows->sum('invoiced');
        $commissions = (float) $rows->sum('commissions');
        $staff = (float) $rows->sum('staff');

        return [
            'rows' => $rows,
            'byCategory' => $byCategory,
            'totals' => [
                'invoiced' => $invoiced,
                'collected' => (float) $rows->sum('collected'),
                'commissions' => $commissions,
                'staff' => $staff,
                'expenses' => $totalExpenses,
                'general_expenses' => $generalExpenses,
                'margin' => $invoiced - $commissions - $staff - $totalExpenses,
            ],
        ];
    }
}
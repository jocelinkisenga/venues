<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Contract;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Space;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    private const OCCUPANCY_DAYS = 30;

    public function for(Venue $venue): array
    {
        $now = now();
        $occupancy = $this->occupancy($venue, $now, self::OCCUPANCY_DAYS);

        return [
            'kpis' => $this->kpis($venue, $now, (float) $occupancy['average']),
            'occupancy' => $occupancy,
            'actions' => $this->actions($venue, $now),
            'upcoming' => $this->upcoming($venue, $now),
            'revenueTrend' => $this->revenueTrend($venue, $now),
            'recentPayments' => $this->recentPayments($venue),
        ];
    }

    private function kpis(Venue $venue, Carbon $now, float $occupancy): array
    {
        $thisMonth = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];

        $previous = $now->copy()->subMonthNoOverflow();
        $previousMonth = [$previous->copy()->startOfMonth(), $previous->copy()->endOfMonth()];

        $current = $this->revenueBetween($venue, ...$thisMonth);
        $past = $this->revenueBetween($venue, ...$previousMonth);

        return [
            'revenue' => $current,
            'revenueDelta' => $past > 0 ? (int) round(($current - $past) / $past * 100) : null,
            'bookings' => Booking::where('venue_id', $venue->id)
                ->where('status', '!=', BookingStatus::Cancelled->value)
                ->whereBetween('starts_at', $thisMonth)
                ->count(),
            'occupancy' => (int) round($occupancy),
            'pendingOptions' => Booking::where('venue_id', $venue->id)
                ->where('status', BookingStatus::Option->value)
                ->where('option_expires_at', '>', $now)
                ->count(),
        ];
    }

    private function revenueBetween(Venue $venue, Carbon $from, Carbon $to): float
    {
        return (float) Payment::where('status', 'succeeded')
            ->whereBetween('paid_at', [$from, $to])
            ->whereHas('booking', fn ($q) => $q->where('venue_id', $venue->id))
            ->sum('amount');
    }

    /** Pour chaque espace : pourcentage de jours occupés sur la période, et moyenne. */
    private function occupancy(Venue $venue, Carbon $now, int $days): array
    {
        $from = $now->copy()->startOfDay();
        $to = $now->copy()->addDays($days)->endOfDay();
        $span = $days + 1;

        $spaces = Space::where('venue_id', $venue->id)->active()->orderBy('name')->get(['id', 'name']);

        $bookings = Booking::where('venue_id', $venue->id)
            ->blocking()
            ->where('starts_at', '<=', $to)
            ->where('ends_at', '>=', $from)
            ->get(['space_id', 'starts_at', 'ends_at']);

        $occupied = [];

        foreach ($bookings as $booking) {
            $cursor = $booking->starts_at->lt($from) ? $from->copy() : $booking->starts_at->copy()->startOfDay();
            $last = $booking->ends_at->gt($to) ? $to->copy() : $booking->ends_at->copy()->startOfDay();

            for ($day = $cursor; $day->lte($last); $day->addDay()) {
                $occupied[$booking->space_id][$day->toDateString()] = true;
            }
        }

        $rows = $spaces->map(fn (Space $space) => [
            'name' => $space->name,
            'percent' => (int) round(count($occupied[$space->id] ?? []) / $span * 100),
        ])->values()->all();

        return [
            'average' => $rows ? (int) round(collect($rows)->avg('percent')) : 0,
            'spaces' => $rows,
        ];
    }

    private function actions(Venue $venue, Carbon $now): array
    {
        $expiringOptions = Booking::where('venue_id', $venue->id)
            ->where('status', BookingStatus::Option->value)
            ->whereBetween('option_expires_at', [$now, $now->copy()->addDay()])
            ->with('space:id,name')
            ->orderBy('option_expires_at')
            ->get();

        $balanceDue = Booking::where('venue_id', $venue->id)
            ->blocking()
            ->whereBetween('starts_at', [$now, $now->copy()->addDays(7)])
            ->with(['quote', 'payments'])
            ->get()
            ->map(fn (Booking $b) => [
                'booking' => $b,
                'due' => round((float) ($b->quote?->total ?? 0) - (float) $b->payments->sum('amount'), 2),
            ])
            ->filter(fn (array $row) => $row['due'] > 0)
            ->values();

        $unsignedContracts = Contract::where('venue_id', $venue->id)
            ->where('status', '!=', 'signed')
            ->whereHas('booking', fn ($q) => $q
                ->where('status', '!=', BookingStatus::Cancelled->value)
                ->whereBetween('starts_at', [$now, $now->copy()->addDays(14)]))
            ->with('booking:id,reference,client_name,starts_at')
            ->get();

        $guestsPending = Guest::where('venue_id', $venue->id)
            ->where('rsvp_status', 'invited')
            ->whereHas('booking', fn ($q) => $q->whereBetween('starts_at', [$now, $now->copy()->addDays(3)]))
            ->with('booking:id,reference,client_name')
            ->get()
            ->groupBy('booking_id')
            ->map(fn (Collection $group) => [
                'booking' => $group->first()->booking,
                'count' => $group->count(),
            ])
            ->values();

        return compact('expiringOptions', 'balanceDue', 'unsignedContracts', 'guestsPending');
    }

    private function upcoming(Venue $venue, Carbon $now): Collection
    {
        return Booking::where('venue_id', $venue->id)
            ->where('status', '!=', BookingStatus::Cancelled->value)
            ->where('ends_at', '>=', $now)
            ->where('starts_at', '<=', $now->copy()->addDays(7))
            ->with('space:id,name')
            ->orderBy('starts_at')
            ->limit(8)
            ->get();
    }

    /** Six derniers mois d'encaissements, avec hauteur relative pour le graphique. */
    private function revenueTrend(Venue $venue, Carbon $now): array
    {
        $start = $now->copy()->subMonths(5)->startOfMonth();

        $totals = Payment::where('status', 'succeeded')
            ->where('paid_at', '>=', $start)
            ->whereHas('booking', fn ($q) => $q->where('venue_id', $venue->id))
            ->get(['amount', 'paid_at'])
            ->groupBy(fn (Payment $p) => $p->paid_at->format('Y-m'))
            ->map(fn (Collection $group) => (float) $group->sum('amount'));

        $months = collect(range(5, 0))->map(function (int $monthsAgo) use ($now, $totals) {
            $month = $now->copy()->subMonthsNoOverflow($monthsAgo);

            return [
                'label' => $month->copy()->locale('fr')->isoFormat('MMM'),
                'amount' => (float) ($totals[$month->format('Y-m')] ?? 0),
            ];
        });

        $max = max(1, (float) $months->max('amount'));

        return $months
            ->map(fn (array $m) => $m + ['height' => (int) round($m['amount'] / $max * 100)])
            ->values()
            ->all();
    }

    private function recentPayments(Venue $venue): Collection
    {
        return Payment::with('booking:id,reference,client_name')
            ->whereHas('booking', fn ($q) => $q->where('venue_id', $venue->id))
            ->latest('paid_at')
            ->limit(6)
            ->get();
    }
}
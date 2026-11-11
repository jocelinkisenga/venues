<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Quote;
use App\Models\SeasonRule;
use App\Models\Venue;
use Carbon\Carbon;

class SeasonalPricing
{
    public function ruleFor(Venue $venue, Carbon $date): ?SeasonRule
    {
        return SeasonRule::where('venue_id', $venue->id)
            ->whereDate('starts_on', '<=', $date->toDateString())
            ->whereDate('ends_on', '>=', $date->toDateString())
            ->orderByDesc('percent')
            ->first();
    }

    /**
     * Ajoute une ligne de majoration ou de réduction calculée sur les forfaits.
     * Ne s'applique qu'une fois par devis : la ligne « Saison » sert de marqueur.
     */
    public function apply(Quote $quote, Booking $booking): void
    {
        if ($quote->lines()->where('label', 'like', 'Saison%')->exists()) {
            return;
        }

        $rule = $this->ruleFor($booking->venue, $booking->starts_at);

        if (! $rule) {
            return;
        }

        $base = (float) $quote->lines()
            ->where('label', 'like', 'Forfait%')
            ->get()
            ->sum(fn ($line) => (float) $line->quantity * (float) $line->unit_price);

        if ($base <= 0) {
            return;
        }

        $quote->lines()->create([
            'label' => "Saison : {$rule->name} ({$rule->percent} %)",
            'quantity' => 1,
            'unit_price' => round($base * (float) $rule->percent / 100, 2),
        ]);
    }
}
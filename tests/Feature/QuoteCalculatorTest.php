<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Package;
use App\Services\QuoteCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class QuoteCalculatorTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    public function test_package_overtime_partner_commission_discount_and_tax(): void
    {
        $venue = $this->makeVenue(); // TVA 18 %
        $space = $this->makeSpace($venue);

        // Forfait 350 000 incluant 8 h ; heure sup. 25 000
        $package = Package::create([
            'venue_id' => $venue->id, 'name' => 'Soirée', 'type' => 'seche', 'time_type' => 'soiree',
            'included_hours' => 8, 'base_price' => 350000, 'overtime_rate' => 25000,
        ]);
        $partner = Partner::create([
            'venue_id' => $venue->id, 'name' => 'Traiteur', 'category' => 'traiteur', 'commission_percent' => 10,
        ]);

        // 16h -> 6h = 14 h, soit 6 h supplémentaires = 150 000
        $booking = $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00', [
            'package_id' => $package->id,
        ]);

        $calculator = app(QuoteCalculator::class);
        $quote = $calculator->createForBooking($booking);

        $quote->lines()->create([
            'partner_id' => $partner->id, 'label' => 'Traiteur buffet', 'quantity' => 1, 'unit_price' => 100000,
        ]);
        $quote->update(['discount_type' => 'percent', 'discount_value' => 10, 'discount_reason' => 'Geste commercial']);

        $quote = $calculator->recalculate($quote->fresh());

        $this->assertEquals(600000, $quote->subtotal);        // 350 000 + 150 000 + 100 000
        $this->assertEquals(60000, $quote->discount_amount);  // 10 %
        $this->assertEquals(97200, $quote->tax_amount);       // 18 % sur 540 000
        $this->assertEquals(637200, $quote->total);
        $this->assertEquals(10000, $quote->commission_total); // 10 % du traiteur
    }

    public function test_fixed_discount_never_exceeds_subtotal(): void
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $booking = $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-05 23:00:00');

        $calculator = app(QuoteCalculator::class);
        $quote = $calculator->createForBooking($booking);
        $quote->lines()->create(['label' => 'Location', 'quantity' => 1, 'unit_price' => 50000]);
        $quote->update(['discount_type' => 'fixed', 'discount_value' => 999999, 'discount_reason' => 'Test']);

        $quote = $calculator->recalculate($quote->fresh());

        $this->assertEquals(50000, $quote->discount_amount);
        $this->assertEquals(0, $quote->total);
    }
}

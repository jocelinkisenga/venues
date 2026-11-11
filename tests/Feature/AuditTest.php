<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Quote;
use App\Services\QuoteCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class AuditTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    public function test_discount_change_is_audited_with_its_reason(): void
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $booking = $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-05 23:00:00');
        $quote = app(QuoteCalculator::class)->createForBooking($booking);

        $quote->update([
            'discount_type' => 'percent', 'discount_value' => 10, 'discount_reason' => 'Client fidèle',
        ]);

        $log = AuditLog::where('auditable_type', Quote::class)
            ->where('action', 'updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame('Client fidèle', $log->changes['discount_reason']);
        $this->assertSame($venue->id, $log->venue_id);
    }
}

<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\CreditNote;
use App\Models\Payment;
use App\Services\CancellationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class CancellationTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    private function paidBooking(int $daysAhead): array
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $user = $this->makeUser($venue, Role::Sales);

        $start = now()->addDays($daysAhead)->setTime(16, 0);
        $booking = $this->makeBooking($venue, $space, $start->toDateTimeString(), $start->copy()->addHours(8)->toDateTimeString());

        Payment::create([
            'booking_id' => $booking->id, 'amount' => 300000, 'kind' => 'deposit',
            'method' => 'bank_transfer', 'paid_at' => now(),
        ]);

        return [$booking, $user];
    }

    public function test_full_refund_more_than_30_days_ahead(): void
    {
        [$booking, $user] = $this->paidBooking(40);

        $cancellation = app(CancellationService::class)->cancel($booking, $user, 'Changement de date', 'credit_note');

        $this->assertSame(300000.0, (float) $cancellation->refund_amount);
        $this->assertSame(100, $cancellation->refund_percent);
        $this->assertCount(1, CreditNote::all());
        $this->assertSame('cancelled', $booking->fresh()->status->value);
    }

    public function test_partial_refund_between_15_and_30_days(): void
    {
        [$booking, $user] = $this->paidBooking(20);

        $cancellation = app(CancellationService::class)->cancel($booking, $user, 'Motif', 'credit_note');

        $this->assertSame(150000.0, (float) $cancellation->refund_amount);
    }

    public function test_no_refund_less_than_15_days_ahead(): void
    {
        [$booking, $user] = $this->paidBooking(10);

        $cancellation = app(CancellationService::class)->cancel($booking, $user, 'Motif', 'credit_note');

        $this->assertSame(0.0, (float) $cancellation->refund_amount);
        $this->assertCount(0, CreditNote::all());
    }
}
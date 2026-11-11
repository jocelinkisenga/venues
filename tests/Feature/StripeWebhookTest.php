<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\QuoteCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Events\WebhookReceived;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    private function optionBookingWithQuote(): Booking
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $booking = $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-05 23:00:00', [
            'status' => BookingStatus::Option,
            'option_expires_at' => now()->addHours(48),
        ]);

        $calculator = app(QuoteCalculator::class);
        $quote = $calculator->createForBooking($booking);
        $quote->lines()->create(['label' => 'Location', 'quantity' => 1, 'unit_price' => 600000]);
        $calculator->recalculate($quote->fresh());

        return $booking;
    }

    private function checkoutPayload(Booking $booking, string $paymentStatus = 'paid', int $amountMinor = 354000): array
    {
        return [
            'id' => 'evt_test_1',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => 'cs_test_1',
                'object' => 'checkout.session',
                'payment_status' => $paymentStatus,
                'payment_intent' => 'pi_test_1',
                'amount_total' => $amountMinor,
                'metadata' => ['booking_id' => (string) $booking->id, 'kind' => 'deposit'],
            ]],
        ];
    }

    public function test_paid_session_records_deposit_once_and_confirms_option(): void
    {
        $booking = $this->optionBookingWithQuote();
        $payload = $this->checkoutPayload($booking);

        // Rejeu du même événement : pas de doublon
        Event::dispatch(new WebhookReceived($payload));
        Event::dispatch(new WebhookReceived($payload));

        $this->assertCount(1, Payment::all());
        $this->assertSame(BookingStatus::Confirmed, $booking->fresh()->status);
    }

    public function test_unpaid_session_is_ignored(): void
    {
        $booking = $this->optionBookingWithQuote();

        Event::dispatch(new WebhookReceived($this->checkoutPayload($booking, 'unpaid')));

        $this->assertCount(0, Payment::all());
        $this->assertSame(BookingStatus::Option, $booking->fresh()->status);
    }
}
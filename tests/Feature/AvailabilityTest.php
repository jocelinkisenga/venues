<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    private function setUpExistingEvent(): array
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        // Soirée 16h -> 6h le lendemain
        $booking = $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00');

        return [$space, $booking];
    }

    public function test_overlapping_booking_is_a_conflict(): void
    {
        [$space] = $this->setUpExistingEvent();

        $conflicts = app(AvailabilityService::class)->conflicts(
            $space, Carbon::parse('2026-12-05 18:00'), Carbon::parse('2026-12-05 23:00')
        );

        $this->assertCount(1, $conflicts);
    }

    public function test_cleaning_buffer_blocks_back_to_back_event(): void
    {
        [$space] = $this->setUpExistingEvent();

        // Le prochain événement démarre à 08h, alors que la salle n'est libérée qu'à 06h + 3h de nettoyage
        $conflicts = app(AvailabilityService::class)->conflicts(
            $space, Carbon::parse('2026-12-06 08:00'), Carbon::parse('2026-12-06 12:00')
        );

        $this->assertCount(1, $conflicts);
    }

    public function test_slot_after_cleaning_is_free(): void
    {
        [$space] = $this->setUpExistingEvent();

        $conflicts = app(AvailabilityService::class)->conflicts(
            $space, Carbon::parse('2026-12-06 10:00'), Carbon::parse('2026-12-06 14:00')
        );

        $this->assertCount(0, $conflicts);
    }

    public function test_expired_option_does_not_block(): void
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00', [
            'status' => BookingStatus::Option,
            'option_expires_at' => now()->subHour(),
        ]);

        $conflicts = app(AvailabilityService::class)->conflicts(
            $space, Carbon::parse('2026-12-05 18:00'), Carbon::parse('2026-12-05 23:00')
        );

        $this->assertCount(0, $conflicts);
    }

    public function test_active_option_blocks(): void
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00', [
            'status' => BookingStatus::Option,
            'option_expires_at' => now()->addHour(),
        ]);

        $conflicts = app(AvailabilityService::class)->conflicts(
            $space, Carbon::parse('2026-12-05 18:00'), Carbon::parse('2026-12-05 23:00')
        );

        $this->assertCount(1, $conflicts);
    }

    public function test_cancelled_booking_does_not_block(): void
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00', [
            'status' => BookingStatus::Cancelled,
        ]);

        $conflicts = app(AvailabilityService::class)->conflicts(
            $space, Carbon::parse('2026-12-05 18:00'), Carbon::parse('2026-12-05 23:00')
        );

        $this->assertCount(0, $conflicts);
    }

    public function test_rescheduling_ignores_the_booking_itself(): void
    {
        [$space, $booking] = $this->setUpExistingEvent();

        app(AvailabilityService::class)->assertAvailable(
            $space,
            Carbon::parse('2026-12-05 16:00'),
            Carbon::parse('2026-12-06 06:00'),
            $booking->id
        );

        $this->assertTrue(true);
    }
}

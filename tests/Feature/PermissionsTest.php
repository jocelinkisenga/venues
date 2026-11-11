<?php

namespace Tests\Feature;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    public function test_technical_user_cannot_access_sales_pages(): void
    {
        $venue = $this->makeVenue();
        $tech = $this->makeUser($venue, Role::Technical);

        $this->actingAs($tech)->get('/crm')->assertForbidden();
        $this->actingAs($tech)->get('/bookings/nouveau')->assertForbidden();
    }

    public function test_technical_user_can_view_beo(): void
    {
        $venue = $this->makeVenue();
        $space = $this->makeSpace($venue);
        $booking = $this->makeBooking($venue, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00');
        $tech = $this->makeUser($venue, Role::Technical);

        $this->actingAs($tech)->get(route('bookings.beo', $booking))->assertOk();
    }

    public function test_user_cannot_open_another_venues_booking(): void
    {
        $venueA = $this->makeVenue();
        $venueB = $this->makeVenue();
        $space = $this->makeSpace($venueA);
        $booking = $this->makeBooking($venueA, $space, '2026-12-05 16:00:00', '2026-12-06 06:00:00');
        $intruder = $this->makeUser($venueB, Role::Owner);

        $this->actingAs($intruder)->get(route('bookings.beo', $booking))->assertNotFound();
    }

    public function test_only_owner_can_see_audit_journal(): void
    {
        $venue = $this->makeVenue();

        $this->actingAs($this->makeUser($venue, Role::Sales))->get('/journal')->assertForbidden();
        $this->actingAs($this->makeUser($venue, Role::Owner))->get('/journal')->assertOk();
    }
}

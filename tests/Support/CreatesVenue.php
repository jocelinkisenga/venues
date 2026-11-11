<?php

namespace Tests\Support;

use App\Enums\BookingStatus;
use App\Enums\Role;
use App\Models\Booking;
use App\Models\Space;
use App\Models\User;
use App\Models\Venue;

trait CreatesVenue
{
    protected function makeVenue(): Venue
    {
        return Venue::create([
            'name' => 'Salle test', 'currency' => 'XOF', 'timezone' => 'Africa/Abidjan',
            'tax_rate' => 18, 'deposit_percent' => 50,
        ]);
    }

    protected function makeUser(Venue $venue, Role $role): User
    {
        return User::create([
            'venue_id' => $venue->id, 'name' => 'Test '.$role->value,
            'email' => uniqid().'@test.local', 'password' => 'password',
            'role' => $role, 'email_verified_at' => now(),
        ]);
    }

    protected function makeSpace(Venue $venue, int $capacity = 200): Space
    {
        return Space::create([
            'venue_id' => $venue->id, 'name' => 'Grande salle', 'zone' => 'grande_salle', 'capacity' => $capacity,
        ]);
    }

    protected function makeBooking(Venue $venue, Space $space, string $start, string $end, array $overrides = []): Booking
    {
        return Booking::create(array_merge([
            'venue_id' => $venue->id,
            'space_id' => $space->id,
            'event_type' => 'Mariage',
            'client_name' => 'Client test',
            'client_phone' => '+2250700000000',
            'guests_count' => 100,
            'reference' => Booking::generateReference(),
            'starts_at' => $start,
            'ends_at' => $end,
            'status' => BookingStatus::Confirmed,
        ], $overrides));
    }
}

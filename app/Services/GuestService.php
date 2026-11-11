<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Support\Str;

class GuestService
{
    public function create(Booking $booking, string $name, ?string $phone): Guest
    {
        return Guest::create([
            'venue_id' => $booking->venue_id,
            'booking_id' => $booking->id,
            'full_name' => Str::squish($name),
            'phone' => $phone ? preg_replace('/[^\d+]/', '', $phone) : null,
            'token' => Str::random(32),
        ]);
    }

    public function respond(Guest $guest, string $status, int $companions): void
    {
        if ($guest->attended_at) {
            throw new \DomainException('Cet invité est déjà entré : sa réponse ne peut plus changer.');
        }

        $guest->update([
            'rsvp_status' => $status,
            'companions' => $status === 'confirmed' ? max(0, $companions) : 0,
        ]);
    }

    /** Valide l'entrée à partir du code du billet (QR scanné ou saisi). */
    public function checkIn(Booking $booking, string $token): Guest
    {
        $guest = Guest::where('booking_id', $booking->id)->where('token', trim($token))->first();

        if (! $guest) {
            throw new \DomainException('Code inconnu pour cet événement.');
        }

        if ($guest->rsvp_status !== 'confirmed') {
            throw new \DomainException("{$guest->full_name} n'a pas confirmé sa présence.");
        }

        if ($guest->attended_at) {
            throw new \DomainException("{$guest->full_name} est déjà entré à {$guest->attended_at->format('H:i')}.");
        }

        $guest->update(['attended_at' => now()]);

        return $guest;
    }
}
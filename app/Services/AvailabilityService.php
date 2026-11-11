<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Space;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class AvailabilityService
{
    /** @return Collection<int, Booking> */
    public function conflicts(Space $space, Carbon $start, Carbon $end, ?int $ignoreBookingId = null): Collection
    {
        return Booking::overlapping($space->id, $start, $end)
            ->when($ignoreBookingId, fn ($q) => $q->whereKeyNot($ignoreBookingId))
            ->get(['id', 'reference', 'starts_at', 'ends_at']);
    }

    public function assertAvailable(Space $space, Carbon $start, Carbon $end, ?int $ignoreBookingId = null): void
    {
        $conflict = $this->conflicts($space, $start, $end, $ignoreBookingId)->first();

        if ($conflict) {
            throw ValidationException::withMessages([
                'starts_at' => "Créneau indisponible : la réservation {$conflict->reference} occupe déjà cette salle (nettoyage inclus).",
            ]);
        }
    }
}

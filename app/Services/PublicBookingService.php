<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\Role;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Space;
use App\Models\Venue;
use App\Notifications\OnlineBookingRequested;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class PublicBookingService
{
    /** Crée une option en ligne : la date est bloquée, la salle confirme ensuite. */
    public function request(Venue $venue, array $data): Booking
    {
        if (! $venue->isListable()) {
            throw new \DomainException("Cette salle n'accepte pas de demandes en ligne pour le moment.");
        }

        $start = Carbon::parse($data['starts_at']);
        $end = Carbon::parse($data['ends_at']);
        $minNotice = (int) config('venue.online_min_notice_hours');

        if ($start->lt(now()->addHours($minNotice))) {
            throw new \DomainException("Les demandes en ligne doivent être faites au moins {$minNotice} heures à l'avance.");
        }

        $space = Space::where('venue_id', $venue->id)->active()->findOrFail($data['space_id']);

        if ((int) $data['guests_count'] > $space->capacity) {
            throw new \DomainException("Capacité maximale de cet espace : {$space->capacity} personnes.");
        }

        if (! empty($data['package_id'])) {
            $package = Package::with('spaces:id')
                ->where('venue_id', $venue->id)
                ->active()
                ->findOrFail($data['package_id']);

            if (! $package->isAvailableIn($space->id)) {
                throw new \DomainException("Ce forfait n'est pas proposé pour cet espace.");
            }
        }

        try {
            app(AvailabilityService::class)->assertAvailable($space, $start, $end);
        } catch (ValidationException $e) {
            throw new \DomainException($e->errors()['starts_at'][0]);
        }

        return DB::transaction(function () use ($venue, $space, $data, $start, $end) {
            $booking = Booking::create([
                'venue_id' => $venue->id,
                'space_id' => $space->id,
                'package_id' => $data['package_id'] ?: null,
                'reference' => Booking::generateReference(),
                'event_type' => $data['event_type'],
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'],
                'client_email' => filled($data['client_email'] ?? null) ? $data['client_email'] : null,
                'guests_count' => $data['guests_count'],
                'starts_at' => $start,
                'ends_at' => $end,
                'status' => BookingStatus::Option,
                'option_expires_at' => now()->addHours((int) config('venue.option_hold_hours')),
                'source' => 'online',
                'notes' => filled($data['message'] ?? null) ? $data['message'] : null,
            ]);

            app(QuoteCalculator::class)->createForBooking($booking);
            $this->notifyTeam($venue, $booking);

            return $booking;
        });
    }

    private function notifyTeam(Venue $venue, Booking $booking): void
    {
        $recipients = $venue->members()->get()
            ->filter(fn ($user) => Role::from($user->pivot->role)->canSell())
            ->values();

        Notification::send($recipients, new OnlineBookingRequested($booking));
    }
}
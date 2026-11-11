<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Venue;
use App\Notifications\BookingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:remind';

    protected $description = 'Alerte l\'équipe commerciale : options qui expirent sous 24 h et événements proches sans acompte.';

    public function handle(): int
    {
        Venue::with('users')->each(function (Venue $venue) {
            $expiring = Booking::where('venue_id', $venue->id)
                ->where('status', BookingStatus::Option->value)
                ->whereBetween('option_expires_at', [now(), now()->addDay()])
                ->get();

            $unpaid = Booking::where('venue_id', $venue->id)
                ->where('status', BookingStatus::Option->value)
                ->whereBetween('starts_at', [now(), now()->addDays(7)])
                ->get();

            if ($expiring->isEmpty() && $unpaid->isEmpty()) {
                return;
            }

            $recipients = $venue->users->filter(fn ($user) => $user->role->canSell());

            Notification::send($recipients, new BookingReminder($expiring, $unpaid));
        });

        $this->info('Rappels envoyés.');

        return self::SUCCESS;
    }
}
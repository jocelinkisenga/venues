<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OnlineBookingRequested extends Notification
{
    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nouvelle demande en ligne · {$this->booking->reference}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("{$this->booking->client_name} demande un(e) {$this->booking->event_type} "
                ."du {$this->booking->starts_at->format('d/m/Y H:i')} "
                ."au {$this->booking->ends_at->format('d/m/Y H:i')} "
                ."pour {$this->booking->guests_count} invités.")
            ->line('La date est bloquée pendant '.config('venue.option_hold_hours').' heures. Préparez le devis et contactez le client.')
            ->action('Ouvrir la réservation', route('bookings.quote', $this->booking));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'reference' => $this->booking->reference,
            'client' => $this->booking->client_name,
        ];
    }
}
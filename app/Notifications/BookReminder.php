<?php

namespace App\Notifications;

use Illuminate\Support\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReminder extends Notification
{
    public function __construct(
        public Collection $expiring,
        public Collection $unpaid,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Rappels réservations')
            ->greeting('Bonjour '.$notifiable->name);

        foreach ($this->expiring as $booking) {
            $mail->line("Option qui expire bientôt : {$booking->reference} ({$booking->client_name}).");
        }

        foreach ($this->unpaid as $booking) {
            $mail->line("Événement dans moins de 7 jours, option sans acompte : {$booking->reference} ({$booking->client_name}).");
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'expiring' => $this->expiring->pluck('reference')->values(),
            'unpaid' => $this->unpaid->pluck('reference')->values(),
        ];
    }
}
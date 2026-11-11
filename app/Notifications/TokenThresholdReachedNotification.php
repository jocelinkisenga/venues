<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TokenThresholdReachedNotification extends Notification
{
    use Queueable;

    public string $type; // '50_percent' ou '10_percent_remaining'
    public int $remainingTokens;

    public function __construct(string $type, int $remainingTokens)
    {
        $this->type = $type;
        $this->remainingTokens = $remainingTokens;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail']; // Mail et/ou base de données pour l'interface Livewire
    }

    public function toMail($notifiable): MailMessage
    {
        $isUrgent = $this->type === '10_percent_remaining';
        
        return (new MailMessage)
            ->subject($isUrgent ? 'Alerte critique : Vos tokens sont presque épuisés !' : 'Alerte : 50% de vos tokens utilisés')
            ->line($isUrgent 
                ? "Attention ! Il ne vous reste plus que 10% de vos tokens ({$this->remainingTokens} tokens)." 
                : "Vous avez consommé plus de 50% de votre quota de tokens pour la période en cours.")
            ->action('Se réabonner', url('/subscription'))
            ->line('Pensez à renouveler votre offre pour éviter toute interruption de service.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => $this->type,
            'remaining_tokens' => $this->remainingTokens,
            'message' => $this->type === '10_percent_remaining'
                ? "Attention ! Il ne vous reste plus que 10% de vos tokens."
                : "Vous avez utilisé 50% de votre quota de tokens.",
        ];
    }
}
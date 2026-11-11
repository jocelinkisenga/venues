<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Option = 'option';
    case Confirmed = 'confirmed';
    case InProgress = 'in_progress';
    case Cleaning = 'cleaning';
    case Finished = 'finished';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Option => 'Option (acompte en attente)',
            self::Confirmed => 'Confirmé',
            self::InProgress => 'En cours',
            self::Cleaning => 'En cours de nettoyage',
            self::Finished => 'Terminé',
            self::Cancelled => 'Annulé',
        };
    }

    /** Statuts qui occupent la salle (anti-conflit). "Libre" = aucune réservation active. */
    public static function blockingValues(): array
    {
        return [self::Option->value, self::Confirmed->value, self::InProgress->value, self::Cleaning->value];
    }
}

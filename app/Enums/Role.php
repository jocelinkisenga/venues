<?php

namespace App\Enums;

enum Role: string
{
    case Owner = 'proprietaire';
    case Sales = 'commercial';
    case Technical = 'technique';

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Propriétaire',
            self::Sales => 'Commercial',
            self::Technical => 'Équipe technique',
        };
    }

    /** Remises, devis, CRM : réservé au propriétaire et au commercial. */
    public function canSell(): bool
    {
        return $this !== self::Technical;
    }
}

<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case VisitPlanned = 'visit_planned';
    case QuoteSent = 'quote_sent';
    case Won = 'won';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Nouveau contact',
            self::VisitPlanned => 'Visite planifiée',
            self::QuoteSent => 'Devis envoyé',
            self::Won => 'Gagné',
            self::Lost => 'Perdu',
        };
    }
}

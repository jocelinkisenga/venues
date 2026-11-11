<?php

namespace App\Support;

final class ApiAbilities
{
    public const SPACES_READ = 'spaces:read';
    public const AVAILABILITY_READ = 'availability:read';
    public const BOOKINGS_READ = 'bookings:read';
    public const GUESTS_READ = 'guests:read';
    public const CHECKINS_WRITE = 'checkins:write';

    public const ALL = [
        self::SPACES_READ,
        self::AVAILABILITY_READ,
        self::BOOKINGS_READ,
        self::GUESTS_READ,
        self::CHECKINS_WRITE,
    ];

    public const LABELS = [
        self::SPACES_READ => 'Lire les espaces',
        self::AVAILABILITY_READ => 'Vérifier les disponibilités',
        self::BOOKINGS_READ => 'Lire les réservations (sans données client)',
        self::GUESTS_READ => 'Lire la liste des invités',
        self::CHECKINS_WRITE => 'Valider les entrées',
    ];
}
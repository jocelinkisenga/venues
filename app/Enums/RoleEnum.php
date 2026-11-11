<?php

namespace App\Enums;

enum RoleEnum: string
{
    case CLIENT = 'client';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::CLIENT => 'Client',
            self::ADMIN  => 'Administrateur',
        };
    }

        public function homePath(): string
    {
        return match ($this) {
            self::CLIENT => '/dashboard',
            self::ADMIN  => '/admin/dashboard',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::CLIENT => 'bg-blue-100 text-blue-700',
            self::ADMIN  => 'bg-purple-100 text-purple-700',
        };
    }
}
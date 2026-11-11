<?php

namespace App\Enums;
enum SubscriptionPlan: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case PREMIUM = 'premium';

    public function label(): string
    {
        return match ($this) {
            self::FREE => 'Free',
            self::PRO => 'Pro',
            self::PREMIUM => 'Premium',
        };
    }
}
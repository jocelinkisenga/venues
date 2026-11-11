<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\User;

class DepositService
{
    /** Restitue la caution (aucune retenue) ou retient un montant pour dégâts. */
    public function settle(Deposit $deposit, User $actor, float $retained, ?string $reason): Deposit
    {
        if ($deposit->status !== 'held') {
            throw new \DomainException('Cette caution est déjà réglée.');
        }

        $retained = min(max(0, $retained), (float) $deposit->amount);

        $deposit->update([
            'status' => $retained > 0 ? 'retained' : 'released',
            'retained_amount' => $retained,
            'reason' => $reason,
            'settled_by' => $actor->id,
            'settled_at' => now(),
        ]);

        return $deposit;
    }
}
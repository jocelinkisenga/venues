<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Models\Venue;

class VenueSwitcher
{
    /** Change la salle active. Le rôle devient celui que l'utilisateur a dans cette salle. */
    public function switchTo(User $user, Venue $venue): void
    {
        $membership = $user->venues()->whereKey($venue->id)->first();

        if (! $membership && ! $user->is_super_admin) {
            throw new \DomainException("Vous n'êtes pas membre de cette salle.");
        }

        if ($venue->isSuspended() && ! $user->is_super_admin) {
            throw new \DomainException('Cette salle est suspendue. Contactez le support.');
        }

        $role = $membership ? Role::from($membership->pivot->role) : Role::Owner;

        $user->update(['venue_id' => $venue->id, 'role' => $role]);
    }
}
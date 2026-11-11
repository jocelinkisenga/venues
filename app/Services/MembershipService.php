<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class MembershipService
{
    /** Seul un propriétaire de la salle (ou un super-administrateur) gère l'équipe. */
    public function assertOwner(Venue $venue, User $actor): void
    {
        $isOwner = $actor->is_super_admin
            || $venue->members()->whereKey($actor->id)->wherePivot('role', Role::Owner->value)->exists();

        abort_unless($isOwner, 403);
    }

    /**
     * Ajoute un membre. Si l'email est inconnu, le compte est créé avec un mot de passe aléatoire
     * et un lien de définition de mot de passe est envoyé.
     */
    public function invite(Venue $venue, User $actor, string $email, Role $role): void
    {
        $this->assertOwner($venue, $actor);

        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => Str::before($email, '@'), 'password' => Hash::make(Str::random(40)), 'role' => $role],
        );

        if ($venue->members()->whereKey($user->id)->exists()) {
            $venue->members()->updateExistingPivot($user->id, ['role' => $role->value]);
        } else {
            $venue->members()->attach($user->id, ['role' => $role->value]);
        }

        if (! $user->venue_id) {
            $user->update(['venue_id' => $venue->id, 'role' => $role]);
        }

        Password::sendResetLink(['email' => $email]);
    }

    public function remove(Venue $venue, User $actor, int $userId): void
    {
        $this->assertOwner($venue, $actor);

        if ($userId === $actor->id) {
            throw new \DomainException('Vous ne pouvez pas retirer votre propre accès.');
        }

        $venue->members()->detach($userId);

        $user = User::find($userId);

        if ($user && $user->venue_id === $venue->id) {
            $next = $user->venues()->whereKeyNot($venue->id)->first();

            $user->update([
                'venue_id' => $next?->id,
                'role' => $next ? Role::from($next->pivot->role) : Role::Sales,
            ]);
        }
    }
}
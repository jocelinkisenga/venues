<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class MembershipService
{
    public function assertOwner(Venue $venue, User $actor): void
    {
        abort_unless($actor->is_super_admin || $this->roleIn($venue, $actor) === Role::Owner, 403);
    }

    public function roleIn(Venue $venue, User $user): ?Role
    {
        $pivot = $venue->members()->whereKey($user->id)->first()?->pivot;

        return $pivot ? Role::from($pivot->role) : null;
    }

    /** Invitation par email : le membre reçoit un lien pour choisir son mot de passe. */
    public function inviteByEmail(Venue $venue, User $actor, string $name, string $email, Role $role): User
    {
        $this->assertOwner($venue, $actor);

        $user = $this->upsert($venue, $name, $email, $role, Str::random(40));

        Password::sendResetLink(['email' => $user->email]);

        return $user;
    }

    /**
     * Création directe avec mot de passe provisoire, pour les membres sans adresse email personnelle.
     * Le mot de passe n'est renvoyé qu'une seule fois, à l'appelant.
     */
    public function createWithTemporaryPassword(Venue $venue, User $actor, string $name, string $email, Role $role): string
    {
        $this->assertOwner($venue, $actor);

        if (User::where('email', Str::lower(trim($email)))->exists()) {
            throw new \DomainException("Un compte existe déjà avec cet email. Utilisez l'invitation par email.");
        }

        $password = Str::password(12, symbols: false);

        $this->upsert($venue, $name, $email, $role, $password);

        return $password;
    }

    /** Conservé pour la page « Mes salles » : invitation par email. */
    public function invite(Venue $venue, User $actor, string $email, Role $role): void
    {
        $this->inviteByEmail($venue, $actor, Str::before($email, '@'), $email, $role);
    }

    public function changeRole(Venue $venue, User $actor, int $userId, Role $role): void
    {
        $this->assertOwner($venue, $actor);
        $this->assertOwnerRemains($venue, $userId, $role);

        $member = $this->memberOrFail($venue, $userId);
        $venue->members()->updateExistingPivot($member->id, ['role' => $role->value]);

        if ($member->venue_id === $venue->id) {
            $member->update(['role' => $role]);
        }
    }

    public function remove(Venue $venue, User $actor, int $userId): void
    {
        $this->assertOwner($venue, $actor);

        if ($userId === $actor->id) {
            throw new \DomainException('Vous ne pouvez pas retirer votre propre accès.');
        }

        $this->assertOwnerRemains($venue, $userId, Role::Sales);

        $member = $this->memberOrFail($venue, $userId);
        $venue->members()->detach($member->id);

        if ($member->venue_id === $venue->id) {
            $next = $member->venues()->whereKeyNot($venue->id)->orderBy('name')->first();

            $member->update([
                'venue_id' => $next?->id,
                'role' => $next ? Role::from($next->pivot->role) : Role::Sales,
            ]);
        }
    }

    /** Crée le compte si besoin, puis le rattache à la salle avec le rôle demandé. */
    private function upsert(Venue $venue, string $name, string $email, Role $role, string $plainPassword): User
    {
        return DB::transaction(function () use ($venue, $name, $email, $role, $plainPassword) {
            $email = Str::lower(trim($email));
            $user = User::where('email', $email)->first();

            if (! $user) {
                // Le cast « hashed » du modèle se charge du hachage
                $user = User::create([
                    'name' => Str::squish($name),
                    'email' => $email,
                    'password' => $plainPassword,
                    'role' => $role,
                    'venue_id' => $venue->id,
                    'email_verified_at' => now(),
                ]);
            }

            // Un membre dont la salle active est celle-ci, ou qui n'en a pas, prend ce rôle
            if (! $user->venue_id || $user->venue_id === $venue->id) {
                $user->update(['venue_id' => $venue->id, 'role' => $role]);
            }

            if (! $user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            if ($venue->members()->whereKey($user->id)->exists()) {
                $venue->members()->updateExistingPivot($user->id, ['role' => $role->value]);
            } else {
                $venue->members()->attach($user->id, ['role' => $role->value]);
            }

            return $user;
        });
    }

    /** Refuse toute opération qui laisserait la salle sans propriétaire. */
    private function assertOwnerRemains(Venue $venue, int $userId, Role $newRole): void
    {
        if ($newRole === Role::Owner) {
            return;
        }

        $target = $this->memberOrFail($venue, $userId);

        if (Role::from($target->pivot->role) !== Role::Owner) {
            return;
        }

        $owners = $venue->members()->wherePivot('role', Role::Owner->value)->count();

        if ($owners <= 1) {
            throw new \DomainException('Une salle doit garder au moins un propriétaire.');
        }
    }

    private function memberOrFail(Venue $venue, int $userId): User
    {
        $member = $venue->members()->whereKey($userId)->first();

        abort_unless($member, 404);

        return $member;
    }
}
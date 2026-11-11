<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['venue_id', 'name', 'email', 'password', 'role', 'phone'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'is_super_admin' => 'boolean',
        ];
    }

    /** Salle actuellement active pour cet utilisateur. */
    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }

    /** Toutes les salles auxquelles l'utilisateur est rattaché. */
    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'venue_user')->withPivot('role')->withTimestamps();
    }
}
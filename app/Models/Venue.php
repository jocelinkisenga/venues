<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = [
        'organization_id', 'name', 'city', 'address', 'country', 'currency', 'timezone', 'phone', 'email',
        'tax_rate', 'deposit_percent', 'nif', 'rccm', 'id_nat', 'suspended_at',
    ];

    protected $casts = ['suspended_at' => 'datetime'];

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }

    /** Membres de la salle, avec leur rôle dans cette salle. */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'venue_user')->withPivot('role')->withTimestamps();
    }

    /** Utilisateurs dont la salle « courante » est celle-ci (ancien lien direct). */
    public function users(): HasMany { return $this->hasMany(User::class); }
    public function spaces(): HasMany { return $this->hasMany(Space::class); }
    public function packages(): HasMany { return $this->hasMany(Package::class); }
    public function partners(): HasMany { return $this->hasMany(Partner::class); }
    public function leads(): HasMany { return $this->hasMany(Lead::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function seasonRules(): HasMany { return $this->hasMany(SeasonRule::class); }

    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    public function money(float|int|string $amount): string
    {
        $decimals = config("venue.currencies.{$this->currency}.decimals", 0);

        return number_format((float) $amount, $decimals, ',', ' ').' '.$this->currency;
    }
}
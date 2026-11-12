<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = [
        'organization_id', 'name', 'slug', 'city', 'address', 'country', 'currency', 'timezone',
        'phone', 'email', 'contact_phone', 'description', 'latitude', 'longitude', 'amenities', 'is_listed',
        'tax_rate', 'deposit_percent', 'nif', 'rccm', 'id_nat', 'suspended_at',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_listed' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'suspended_at' => 'datetime',
    ];

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'venue_user')->withPivot('role')->withTimestamps();
    }

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function spaces(): HasMany { return $this->hasMany(Space::class); }
    public function packages(): HasMany { return $this->hasMany(Package::class); }
    public function partners(): HasMany { return $this->hasMany(Partner::class); }
    public function leads(): HasMany { return $this->hasMany(Lead::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function seasonRules(): HasMany { return $this->hasMany(SeasonRule::class); }

    public function photos(): HasMany
    {
        return $this->hasMany(VenuePhoto::class)->orderBy('position')->orderBy('id');
    }

    /** Salles visibles publiquement : publiées, non suspendues, avec une adresse publique. */
    public function scopeListed(Builder $query): Builder
    {
        return $query->where('is_listed', true)
            ->whereNull('suspended_at')
            ->whereNotNull('slug');
    }

    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    /** Éléments nécessaires avant publication. Libellé => validé. */
    public function profileChecklist(): array
    {
        return [
            'Nom, ville et adresse' => filled($this->name) && filled($this->city) && filled($this->address),
            'Description (40 caractères minimum)' => mb_strlen((string) $this->description) >= 40,
            'Téléphone de contact' => filled($this->contact_phone),
            'Au moins une photo' => $this->photos()->exists(),
            'Au moins un espace actif' => $this->spaces()->active()->exists(),
        ];
    }

    public function isProfileComplete(): bool
    {
        return ! in_array(false, $this->profileChecklist(), true);
    }

    public function isListable(): bool
    {
        return $this->is_listed
            && ! $this->isSuspended()
            && filled($this->slug)
            && $this->isProfileComplete();
    }

    public function publicUrl(): ?string
    {
        return $this->slug ? route('explorer.show', $this->slug) : null;
    }

    public function money(float|int|string $amount): string
    {
        $decimals = config("venue.currencies.{$this->currency}.decimals", 0);

        return number_format((float) $amount, $decimals, ',', ' ').' '.$this->currency;
    }
}
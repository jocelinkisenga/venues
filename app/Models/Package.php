<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    protected $fillable = [
        'venue_id', 'name', 'type', 'time_type', 'days', 'included_hours',
        'base_price', 'overtime_rate', 'includes', 'archived_at',
    ];

    protected $casts = [
        'includes' => 'array',
        'base_price' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'archived_at' => 'datetime',
    ];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }

    public function spaces(): BelongsToMany { return $this->belongsToMany(Space::class, 'package_space'); }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    /** Sans espace associé, le forfait est proposé partout. */
    public function isAvailableIn(?int $spaceId): bool
    {
        if ($spaceId === null) {
            return true;
        }

        if ($this->relationLoaded('spaces')) {
            return $this->spaces->isEmpty() || $this->spaces->contains('id', $spaceId);
        }

        return ! $this->spaces()->exists() || $this->spaces()->whereKey($spaceId)->exists();
    }
}
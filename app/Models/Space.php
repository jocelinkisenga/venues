<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    protected $fillable = ['venue_id', 'name', 'zone', 'capacity', 'archived_at'];

    protected $casts = ['archived_at' => 'datetime'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function packages(): BelongsToMany { return $this->belongsToMany(Package::class, 'package_space'); }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }
}
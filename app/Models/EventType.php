<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventType extends Model
{
    protected $fillable = ['venue_id', 'name', 'archived_at'];

    protected $casts = ['archived_at' => 'datetime'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }
}
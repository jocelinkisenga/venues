<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FloorPlan extends Model
{
    protected $fillable = ['booking_id', 'name', 'width', 'height', 'revision', 'published_at'];

    protected $casts = ['published_at' => 'datetime'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }

    public function tables(): HasMany
    {
        return $this->hasMany(FloorTable::class)->orderBy('id');
    }
}
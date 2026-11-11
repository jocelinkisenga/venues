<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    protected $fillable = ['venue_id', 'name', 'zone', 'capacity'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
}

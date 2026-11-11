<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    protected $fillable = [
        'venue_id', 'booking_id', 'full_name', 'phone', 'token', 'rsvp_status', 'companions', 'attended_at',
    ];

    protected $casts = ['attended_at' => 'datetime'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }

    /** Nombre de personnes admises avec ce billet : l'invité et ses accompagnants. */
    public function admittedCount(): int
    {
        return $this->rsvp_status === 'confirmed' ? 1 + $this->companions : 0;
    }
}
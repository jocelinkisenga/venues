<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    protected $fillable = [
        'venue_id', 'booking_id', 'floor_table_id', 'full_name', 'phone', 'token',
        'rsvp_status', 'companions', 'attended_at',
    ];

    protected $casts = ['attended_at' => 'datetime'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function floorTable(): BelongsTo { return $this->belongsTo(FloorTable::class, 'floor_table_id'); }

    /** Nombre de chaises occupées par cet invité : lui-même et ses accompagnants. */
    public function seatCount(): int
    {
        return 1 + (int) $this->companions;
    }

    public function admittedCount(): int
    {
        return $this->rsvp_status === 'confirmed' ? $this->seatCount() : 0;
    }
}
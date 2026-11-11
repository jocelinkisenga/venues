<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'venue_id', 'assigned_to', 'full_name', 'phone', 'email', 'event_type',
        'expected_guests', 'event_date', 'source', 'status', 'lost_reason', 'notes',
    ];

    protected $casts = ['status' => LeadStatus::class, 'event_date' => 'date'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
}

<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use Auditable;

    protected $fillable = ['booking_id', 'amount', 'status', 'retained_amount', 'reason', 'settled_by', 'settled_at'];

    protected $casts = ['settled_at' => 'datetime'];

    public function auditVenueId(): ?int { return $this->booking?->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
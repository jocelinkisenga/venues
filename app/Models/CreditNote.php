<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use Auditable;

    protected $fillable = ['venue_id', 'booking_id', 'number', 'amount', 'reason'];

    public function auditVenueId(): ?int { return $this->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
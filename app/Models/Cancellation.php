<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cancellation extends Model
{
    use Auditable;

    protected $fillable = [
        'booking_id', 'cancelled_by', 'reason', 'refund_percent', 'paid_amount', 'refund_amount', 'mode',
    ];

    public function auditVenueId(): ?int { return $this->booking?->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
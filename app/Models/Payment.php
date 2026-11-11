<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use Auditable;

    protected $fillable = [
        'booking_id', 'payer_id', 'recorded_by', 'amount', 'kind', 'method',
        'provider', 'status', 'reference', 'paid_at',
    ];

    protected $casts = ['paid_at' => 'datetime', 'amount' => 'decimal:2'];

    public function auditVenueId(): ?int { return $this->booking?->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function payer(): BelongsTo { return $this->belongsTo(Payer::class); }
}
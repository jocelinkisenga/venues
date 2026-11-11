<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use Auditable;

    protected $fillable = [
        'booking_id', 'subtotal', 'discount_type', 'discount_value', 'discount_amount',
        'discount_reason', 'discount_by', 'tax_amount', 'commission_total', 'total',
    ];

    public function auditVenueId(): ?int { return $this->booking?->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function lines(): HasMany { return $this->hasMany(QuoteLine::class); }
}

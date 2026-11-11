<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use Auditable;

    protected $fillable = [
        'venue_id', 'booking_id', 'recorded_by', 'category', 'label', 'supplier', 'amount', 'spent_on',
    ];

    protected $casts = ['amount' => 'decimal:2', 'spent_on' => 'date'];

    public function auditVenueId(): ?int { return $this->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use Auditable;

    protected $fillable = [
        'venue_id', 'booking_id', 'number', 'status', 'body', 'sent_at',
        'signed_name', 'signed_at', 'signed_ip', 'signed_user_agent', 'document_hash',
    ];

    protected $casts = ['sent_at' => 'datetime', 'signed_at' => 'datetime'];

    public function auditVenueId(): ?int { return $this->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
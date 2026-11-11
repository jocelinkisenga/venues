<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use Auditable;

    protected $fillable = ['venue_id', 'booking_id', 'staff_member_id', 'role', 'pay_amount', 'paid_at'];

    protected $casts = ['pay_amount' => 'decimal:2', 'paid_at' => 'datetime'];

    public function auditVenueId(): ?int { return $this->venue_id; }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function staffMember(): BelongsTo { return $this->belongsTo(StaffMember::class); }
}
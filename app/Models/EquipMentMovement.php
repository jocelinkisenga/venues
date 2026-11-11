<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentMovement extends Model
{
    use Auditable;

    public const UPDATED_AT = null;

    protected $fillable = ['venue_id', 'equipment_item_id', 'booking_id', 'user_id', 'type', 'quantity', 'delta', 'note'];

    protected $casts = ['created_at' => 'datetime'];

    public function auditVenueId(): ?int { return $this->venue_id; }

    public function item(): BelongsTo { return $this->belongsTo(EquipmentItem::class, 'equipment_item_id'); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
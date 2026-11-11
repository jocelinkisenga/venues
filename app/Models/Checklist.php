<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklist extends Model
{
    protected $fillable = ['booking_id', 'phase', 'items', 'completed_by', 'completed_at'];

    protected $casts = ['items' => 'array', 'completed_at' => 'datetime'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }

    public function isComplete(): bool
    {
        return collect($this->items)->every(fn ($item) => $item['done']);
    }
}

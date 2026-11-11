<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatingTable extends Model
{
    protected $fillable = ['booking_id', 'layout', 'label', 'shape', 'seats', 'x', 'y', 'is_vip'];

    protected $casts = ['is_vip' => 'boolean'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}

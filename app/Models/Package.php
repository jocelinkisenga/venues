<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    protected $fillable = ['venue_id', 'name', 'type', 'time_type', 'days', 'included_hours', 'base_price', 'overtime_rate', 'includes'];

    protected $casts = ['includes' => 'array', 'base_price' => 'decimal:2', 'overtime_rate' => 'decimal:2'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
}

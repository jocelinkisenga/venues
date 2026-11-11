<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonRule extends Model
{
    protected $fillable = ['venue_id', 'name', 'starts_on', 'ends_on', 'percent'];

    protected $casts = ['starts_on' => 'date', 'ends_on' => 'date', 'percent' => 'decimal:2'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
}
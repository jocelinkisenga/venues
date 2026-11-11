<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $fillable = ['venue_id', 'name', 'category', 'commission_percent', 'phone'];

    protected $casts = ['commission_percent' => 'decimal:2'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
}

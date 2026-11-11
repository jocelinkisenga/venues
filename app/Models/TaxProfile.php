<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxProfile extends Model
{
    protected $fillable = [
        'user_id',
        'tax_year',
        'data',
        'scores',
        'recommended_tier',
        'ai_summary'
    ];

    protected $casts = [
        'data' => 'array',
        'scores' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
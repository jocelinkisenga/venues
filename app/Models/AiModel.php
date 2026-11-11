<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiModel extends Model
{
    protected $fillable = [
        'ai_provider_id',
        'name',
        'model_identifier',
        'label',
        'is_enabled',
        'priority',
        'max_input_tokens',
        'max_output_tokens',
        'input_price_per_million',
        'output_price_per_million',
        'capabilities',
        'metadata',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'capabilities' => 'array',
        'metadata' => 'array',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }
}
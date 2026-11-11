<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiSetting extends Model
{
    protected $fillable = [
        'primary_model_id',
        'fallback_model_ids',
        'fallback_enabled',
        'automatic_failover',
        'max_retries',
        'health_cooldown_seconds',
        'updated_by',
    ];

    protected $casts = [
        'fallback_model_ids' => 'array',
        'fallback_enabled' => 'boolean',
        'automatic_failover' => 'boolean',
    ];

    public function primaryModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class, 'primary_model_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'fallback_model_ids' => [],
                'fallback_enabled' => true,
                'automatic_failover' => true,
                'max_retries' => 2,
                'health_cooldown_seconds' => 300,
            ]
        );
    }
}
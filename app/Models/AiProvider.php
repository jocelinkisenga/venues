<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiProvider extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'driver',
        'is_enabled',
        'status',
        'last_health_check_at',
        'last_error',
        'metadata',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'last_health_check_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(AiModel::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}
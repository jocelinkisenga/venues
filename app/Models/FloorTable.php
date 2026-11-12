<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FloorTable extends Model
{
    protected $fillable = ['floor_plan_id', 'name', 'shape', 'category', 'seats', 'x', 'y', 'width', 'height'];

    public function plan(): BelongsTo { return $this->belongsTo(FloorPlan::class, 'floor_plan_id'); }

    public function guests(): HasMany { return $this->hasMany(Guest::class, 'floor_table_id'); }
}
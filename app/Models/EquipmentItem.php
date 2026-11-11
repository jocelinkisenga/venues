<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentItem extends Model
{
    protected $fillable = ['venue_id', 'name', 'category', 'replacement_cost'];

    protected $casts = ['replacement_cost' => 'decimal:2'];

    public function movements(): HasMany { return $this->hasMany(EquipmentMovement::class); }

    public function stock(): int
    {
        return (int) $this->movements()->sum('delta');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffMember extends Model
{
    protected $fillable = ['venue_id', 'name', 'role', 'phone', 'default_rate'];

    protected $casts = ['default_rate' => 'decimal:2'];

    public function assignments(): HasMany { return $this->hasMany(Assignment::class); }
}
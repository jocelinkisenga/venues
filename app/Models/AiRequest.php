<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRequest extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['venue_id', 'user_id', 'feature', 'model', 'status', 'duration_ms'];
}
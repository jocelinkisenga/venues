<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['venue_id', 'client_id', 'guest_id', 'result', 'scanned_at'];

    protected $casts = ['scanned_at' => 'datetime', 'created_at' => 'datetime'];
}
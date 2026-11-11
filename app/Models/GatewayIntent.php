<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayIntent extends Model
{
    protected $fillable = [
        'booking_id', 'transaction_id', 'provider', 'amount', 'currency', 'kind', 'status', 'payment_url',
    ];

    protected $casts = ['amount' => 'decimal:2'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
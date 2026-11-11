<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteLine extends Model
{
    protected $fillable = [
        'quote_id', 'partner_id', 'label', 'quantity', 'unit_price', 'line_total',
        'commission_percent', 'commission_amount',
    ];

    public function quote(): BelongsTo { return $this->belongsTo(Quote::class); }
    public function partner(): BelongsTo { return $this->belongsTo(Partner::class); }
}

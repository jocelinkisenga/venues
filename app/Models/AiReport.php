<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiReport extends Model
{
    protected $table = 'ai_reports';

    protected $fillable = [
        'user_id',
        'chat_id',
        'title',
        'summary',
        'content',
        'type',
        'status',
        'source_file',
        'pdf_path',
        'model',
        'confidence_score',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'confidence_score' => 'integer',
    ];

    /**
     * User owner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parent chat
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    
    public function hasPdf(): bool
{
    return !empty($this->pdf_path);
}
    
    public function isCompleted(): bool
{
    return $this->status === 'completed';
}
}
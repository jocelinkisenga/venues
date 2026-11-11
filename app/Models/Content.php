<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'thumbnail',
        'excerpt',
        'content',
        'video_url',
        'document_path',
        'access_level',
        'visibility',
        'status',
        'featured',
        'category_id',
        'created_by',
        'published_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($content) {
            $content->slug = Str::slug($content->title);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isPremium(): bool
    {
        return $this->access_level !== 'free';
    }
    
    public function savedByUsers()
{
    return $this->belongsToMany(
        User::class,
        'saved_contents'
    )->withTimestamps();
}

public function getReadTimeAttribute()
{
    $text = strip_tags($this->content);
    $wordCount = str_word_count($text);
    $minutes = $wordCount / 200;
    
    
    $imageCount = substr_count($this->content, '<img');
    $minutes += ($imageCount * 0.2);
    
    return ceil($minutes) . ' min';
}
}
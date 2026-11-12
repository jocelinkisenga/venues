<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VenuePhoto extends Model
{
    protected $fillable = ['venue_id', 'path', 'position'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }

    // public function url(): string
    // {
    //     return Storage::disk('public')->url($this->path);
    // }
    public function url(): string
{
    // Adresse relative : fonctionne quel que soit l'hôte, le port ou APP_URL
    return '/storage/'.ltrim($this->path, '/');
}
}
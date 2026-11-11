<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Groupe qui possède une ou plusieurs salles. Porte l'abonnement SaaS (Partie B). */
class Organization extends Model
{
    protected $fillable = ['name', 'slug', 'owner_id'];

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function venues(): HasMany { return $this->hasMany(Venue::class); }
}
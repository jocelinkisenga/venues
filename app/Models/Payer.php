<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

/** Client de la salle qui paie par carte. Pas de compte utilisateur requis. */
class Payer extends Model
{
    use Billable;

    protected $fillable = ['venue_id', 'name', 'phone', 'email'];

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function cardPayments(): HasMany { return $this->hasMany(Payment::class); }

    /** Nom affiché dans le tableau de bord Stripe. */
    public function stripeName(): ?string
    {
        return $this->name;
    }
}
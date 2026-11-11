<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = ['name', 'city', 'country', 'currency', 'timezone', 'phone', 'tax_rate', 'deposit_percent'];

    public function spaces(): HasMany { return $this->hasMany(Space::class); }
    public function packages(): HasMany { return $this->hasMany(Package::class); }
    public function partners(): HasMany { return $this->hasMany(Partner::class); }
    public function leads(): HasMany { return $this->hasMany(Lead::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function users(): HasMany { return $this->hasMany(User::class); }

    /** Formate un montant selon la devise de la salle (ex : 350 000 XOF). */
    public function money(float|int|string $amount): string
    {
        $decimals = config("venue.currencies.{$this->currency}.decimals", 0);

        return number_format((float) $amount, $decimals, ',', ' ').' '.$this->currency;
    }
}

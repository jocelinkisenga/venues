<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Models\Concerns\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use Auditable;

    protected $fillable = [
        'venue_id', 'space_id', 'package_id', 'lead_id', 'created_by', 'reference', 'event_type',
        'client_name', 'client_phone', 'guests_count', 'starts_at', 'ends_at', 'status',
        'option_expires_at', 'notes','source', 'client_email',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'option_expires_at' => 'datetime',
    ];

    public function auditVenueId(): ?int { return $this->venue_id; }

    public function venue(): BelongsTo { return $this->belongsTo(Venue::class); }
    public function space(): BelongsTo { return $this->belongsTo(Space::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function quote(): HasOne { return $this->hasOne(Quote::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function seatingTables(): HasMany { return $this->hasMany(SeatingTable::class); }
    public function checklists(): HasMany { return $this->hasMany(Checklist::class); }

    /** Réservations qui bloquent réellement le calendrier (option expirée exclue). */
    public function scopeBlocking(Builder $query): Builder
    {
        return $query->whereIn('status', BookingStatus::blockingValues())
            ->where(fn (Builder $q) => $q->where('status', '!=', BookingStatus::Option->value)
                ->orWhere('option_expires_at', '>', now()));
    }

    /**
     * Anti-conflit avec temps de nettoyage après chaque événement (cleaning_buffer_hours).
     * Conflit si : existant.début < fin + tampon ET existant.fin + tampon > début.
     */
    public function scopeOverlapping(Builder $query, int $spaceId, Carbon $start, Carbon $end): Builder
    {
        $buffer = (int) config('venue.cleaning_buffer_hours');

        return $query->blocking()
            ->where('space_id', $spaceId)
            ->where('starts_at', '<', $end->copy()->addHours($buffer)->toDateTimeString())
            ->where('ends_at', '>', $start->copy()->subHours($buffer)->toDateTimeString());
    }

    public static function generateReference(): string
    {
        return 'EVT-'.now()->format('ymd').'-'.Str::upper(Str::random(4));
    }

    public function contract(): HasOne { return $this->hasOne(Contract::class); }
public function guests(): HasMany { return $this->hasMany(Guest::class); }
public function floorPlan(): HasOne { return $this->hasOne(FloorPlan::class); }
}

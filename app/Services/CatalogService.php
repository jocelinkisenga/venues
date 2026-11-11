<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\EventType;
use App\Models\Package;
use App\Models\Space;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogService
{
    public function seedDefaults(Venue $venue): void
    {
        foreach (config('venue.event_types', []) as $name) {
            EventType::firstOrCreate(['venue_id' => $venue->id, 'name' => $name]);
        }
    }

    /* ---------- Espaces ---------- */

    public function addSpace(Venue $venue, string $name, int $capacity, string $zone): Space
    {
        return Space::create([
            'venue_id' => $venue->id,
            'name' => Str::squish($name),
            'zone' => $zone,
            'capacity' => $capacity,
        ]);
    }

    public function updateSpace(Space $space, array $data): Space
    {
        $space->update([
            'name' => Str::squish($data['name']),
            'zone' => $data['zone'],
            'capacity' => (int) $data['capacity'],
        ]);

        return $space;
    }

    /** Un espace ne s'archive pas s'il porte encore des réservations à venir. */
    public function archiveSpace(Space $space): void
    {
        $upcoming = Booking::where('space_id', $space->id)
            ->blocking()
            ->where('ends_at', '>', now())
            ->count();

        if ($upcoming > 0) {
            throw new \DomainException("Impossible d'archiver : {$upcoming} réservation(s) à venir utilisent cet espace.");
        }

        $space->update(['archived_at' => now()]);
    }

    public function restoreSpace(Space $space): void
    {
        $space->update(['archived_at' => null]);
    }

    /* ---------- Types d'événements ---------- */

    public function addEventType(Venue $venue, string $name): EventType
    {
        $name = Str::limit(Str::squish($name), 80, '');

        if (mb_strlen($name) < 2) {
            throw new \DomainException('Le nom du type d\'événement est trop court.');
        }

        return EventType::firstOrCreate(['venue_id' => $venue->id, 'name' => $name]);
    }

    /**
     * Les réservations gardent le nom d'origine (stocké en texte) :
     * renommer un type ne modifie pas l'historique.
     */
    public function renameEventType(EventType $type, string $name): EventType
    {
        $type->update(['name' => Str::limit(Str::squish($name), 80, '')]);

        return $type;
    }

    public function archiveEventType(EventType $type): void
    {
        $type->update(['archived_at' => now()]);
    }

    public function restoreEventType(EventType $type): void
    {
        $type->update(['archived_at' => null]);
    }

    /* ---------- Forfaits ---------- */

    /** @param  array<int>  $spaceIds  vide = forfait proposé pour tous les espaces */
    public function addPackage(Venue $venue, array $data, array $spaceIds = []): Package
    {
        return DB::transaction(function () use ($venue, $data, $spaceIds) {
            $package = Package::create($data + ['venue_id' => $venue->id]);
            $package->spaces()->sync($this->validSpaceIds($venue, $spaceIds));

            return $package;
        });
    }

    /**
     * Les modifications de prix ne touchent pas les devis déjà émis :
     * le devis garde le prix qui a été communiqué au client.
     */
    public function updatePackage(Package $package, array $data, array $spaceIds): Package
    {
        return DB::transaction(function () use ($package, $data, $spaceIds) {
            $package->update($data);
            $package->spaces()->sync($this->validSpaceIds($package->venue, $spaceIds));

            return $package;
        });
    }

    public function archivePackage(Package $package): void
    {
        $package->update(['archived_at' => now()]);
    }

    public function restorePackage(Package $package): void
    {
        $package->update(['archived_at' => null]);
    }

    private function validSpaceIds(Venue $venue, array $spaceIds): array
    {
        return Space::where('venue_id', $venue->id)->whereIn('id', $spaceIds)->pluck('id')->all();
    }
}
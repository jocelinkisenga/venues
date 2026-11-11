<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VenueProvisioner
{
    /** Crée une salle rattachée à l'organisation du propriétaire, puis bascule dessus. */
    public function create(User $owner, array $attributes): Venue
    {
        return DB::transaction(function () use ($owner, $attributes) {
            $organization = Organization::firstOrCreate(
                ['owner_id' => $owner->id],
                ['name' => $owner->name, 'slug' => Str::slug($owner->name).'-'.$owner->id],
            );

            $venue = Venue::create($attributes + [
                'organization_id' => $organization->id,
                'tax_rate' => config('venue.default_tax_rate'),
                'deposit_percent' => config('venue.deposit_percent'),
            ]);
            app(\App\Services\CatalogService::class)->seedDefaults($venue);
            $owner->venues()->attach($venue->id, ['role' => 'proprietaire']);

            app(VenueSwitcher::class)->switchTo($owner->fresh(), $venue);

            return $venue;
        });
    }
}
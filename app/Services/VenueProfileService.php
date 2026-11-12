<?php

namespace App\Services;

use App\Models\Venue;
use App\Models\VenuePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VenueProfileService
{
    /** Adresse publique stable : générée une seule fois, à partir du nom. */
    public function uniqueSlug(string $name, int $venueId): string
    {
        $base = Str::slug($name) ?: 'salle';
        $slug = $base;
        $i = 2;

        while (Venue::where('slug', $slug)->where('id', '!=', $venueId)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function addPhoto(Venue $venue, UploadedFile $file): VenuePhoto
    {
        if ($venue->photos()->count() >= config('venue.online_max_photos')) {
            throw new \DomainException('Limite de photos atteinte pour cette salle.');
        }

        $path = $file->store("venues/{$venue->id}", 'public');

        $max = VenuePhoto::where('venue_id', $venue->id)->max('position');

        return VenuePhoto::create([
            'venue_id' => $venue->id,
            'path' => $path,
            'position' => $max === null ? 0 : $max + 1,
        ]);
    }

    public function deletePhoto(VenuePhoto $photo): void
    {
        $venue = $photo->venue;

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        $this->renumber($venue->photos()->get());
    }

    /** La photo choisie devient la première, donc la couverture de la page. */
    public function setCover(VenuePhoto $photo): void
    {
        $ordered = $photo->venue->photos()->get()
            ->reject(fn (VenuePhoto $p) => $p->id === $photo->id)
            ->prepend($photo);

        $this->renumber($ordered);
    }

    private function renumber(Collection $photos): void
    {
        foreach ($photos->values() as $index => $photo) {
            if ($photo->position !== $index) {
                $photo->update(['position' => $index]);
            }
        }
    }
}
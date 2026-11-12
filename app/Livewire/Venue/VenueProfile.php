<?php

namespace App\Livewire\Venue;

use App\Enums\Role;
use App\Models\Venue;
use App\Models\VenuePhoto;
use App\Services\VenueProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class VenueProfile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $city = '';
    public string $address = '';
    public string $country = 'CD';
    public string $contact_phone = '';
    public string $email = '';
    public string $description = '';
    public string $latitude = '';
    public string $longitude = '';
    public array $amenities = [];
    public bool $is_listed = false;

    // Confidentiel : jamais affiché publiquement
    public string $nif = '';
    public string $rccm = '';
    public string $id_nat = '';

    public array $newPhotos = [];
    public ?string $notice = null;

    public function mount(): void
    {
        abort_unless(Auth::user()->role === Role::Owner, 403);

        $venue = $this->venue();

        $this->name = (string) $venue->name;
        $this->city = (string) $venue->city;
        $this->address = (string) $venue->address;
        $this->country = (string) ($venue->country ?: 'CD');
        $this->contact_phone = (string) $venue->contact_phone;
        $this->email = (string) $venue->email;
        $this->description = (string) $venue->description;
        $this->latitude = $venue->latitude !== null ? (string) $venue->latitude : '';
        $this->longitude = $venue->longitude !== null ? (string) $venue->longitude : '';
        $this->amenities = $venue->amenities ?? [];
        $this->is_listed = (bool) $venue->is_listed;
        $this->nif = (string) $venue->nif;
        $this->rccm = (string) $venue->rccm;
        $this->id_nat = (string) $venue->id_nat;
    }

    public function save(VenueProfileService $profile): void
    {
        $this->notice = null;
        $venue = $this->venue();

        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:200'],
            'country' => ['required', 'string', 'size:2'],
            'contact_phone' => ['nullable', 'string', 'max:30', 'regex:/^\+?[0-9\s]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'amenities' => ['array'],
            'amenities.*' => [Rule::in(array_keys(config('venue.amenities')))],
            'is_listed' => ['boolean'],
            'nif' => ['nullable', 'string', 'max:30'],
            'rccm' => ['nullable', 'string', 'max:40'],
            'id_nat' => ['nullable', 'string', 'max:30'],
        ]);

        $venue->update([
            'name' => $data['name'],
            'slug' => $venue->slug ?: $profile->uniqueSlug($data['name'], $venue->id),
            'city' => $this->nullable($data['city']),
            'address' => $this->nullable($data['address']),
            'country' => $data['country'],
            'contact_phone' => $this->nullable($data['contact_phone']),
            'email' => $this->nullable($data['email']),
            'description' => $this->nullable($data['description']),
            'latitude' => $data['latitude'] !== '' && $data['latitude'] !== null ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== '' && $data['longitude'] !== null ? $data['longitude'] : null,
            'amenities' => array_values($data['amenities'] ?? []),
            'nif' => $this->nullable($data['nif']),
            'rccm' => $this->nullable($data['rccm']),
            'id_nat' => $this->nullable($data['id_nat']),
        ]);

        // La publication n'est accordée que si la checklist est complète
        if ($data['is_listed'] && ! $venue->isProfileComplete()) {
            $venue->update(['is_listed' => false]);
            $this->addError('is_listed', 'Complétez la checklist ci-dessous avant de publier la salle.');
        } else {
            $venue->update(['is_listed' => (bool) $data['is_listed']]);
            $this->notice = 'Profil enregistré.';
        }

        $this->is_listed = (bool) $venue->fresh()->is_listed;
    }

    public function uploadPhotos(VenueProfileService $profile): void
    {
        $this->notice = null;

        $this->validate([
            'newPhotos' => ['required', 'array', 'max:10'],
            'newPhotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        try {
            foreach ($this->newPhotos as $file) {
                $profile->addPhoto($this->venue(), $file);
            }
        } catch (\DomainException $e) {
            $this->addError('newPhotos', $e->getMessage());

            return;
        }

        $this->reset('newPhotos');
        $this->notice = 'Photos ajoutées.';
    }

    public function setCover(int $photoId, VenueProfileService $profile): void
    {
        $profile->setCover($this->ownPhoto($photoId));
    }

    public function deletePhoto(int $photoId, VenueProfileService $profile): void
    {
        $profile->deletePhoto($this->ownPhoto($photoId));
    }

    public function render()
    {
        $venue = $this->venue();

        return view('livewire.venue.venue-profile', [
            'venue' => $venue,
            'photos' => $venue->photos,
            'checklist' => $venue->profileChecklist(),
            'amenityOptions' => config('venue.amenities'),
            'maxPhotos' => config('venue.online_max_photos'),
            'publicUrl' => $venue->publicUrl(),
        ])->layout('layouts.app');
    }

    private function venue(): Venue
    {
        return Auth::user()->venue;
    }

    private function ownPhoto(int $id): VenuePhoto
    {
        return VenuePhoto::where('venue_id', $this->venue()->id)->findOrFail($id);
    }

    private function nullable(?string $value): ?string
    {
        return filled($value) ? trim($value) : null;
    }
}
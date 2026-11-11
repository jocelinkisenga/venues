<?php

namespace App\Livewire\Onboarding;

use App\Services\VenueProvisioner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateFirstVenue extends Component
{
    public string $name = '';
    public string $city = '';
    public string $country = 'CD';
    public string $currency = 'CDF';
    public string $timezone = 'Africa/Lubumbashi';

    public function mount(): void
    {
        // Un utilisateur qui a déjà une salle n'a rien à faire ici
        if (Auth::user()->venue_id) {
            $this->redirect(route('dashboard'));
        }
    }

    public function create(VenueProvisioner $provisioner)
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:80'],
            'country' => ['required', 'string', 'size:2'],
            'currency' => ['required', Rule::in(array_keys(config('venue.currencies')))],
            'timezone' => ['required', 'timezone'],
        ]);

        $provisioner->create(Auth::user(), [
            'name' => $data['name'],
            'city' => $data['city'] ?: null,
            'country' => $data['country'],
            'currency' => $data['currency'],
            'timezone' => $data['timezone'],
        ]);

        return $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.onboarding.create-first-venue', [
            'currencies' => config('venue.currencies'),
        ])->layout('layouts.onboarding');
    }
}
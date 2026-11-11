<?php

namespace App\Livewire\Venues;

use App\Enums\Role;
use App\Models\Venue;
use App\Services\MembershipService;
use App\Services\VenueProvisioner;
use App\Services\VenueSwitcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MyVenues extends Component
{
    public string $name = '';
    public string $city = '';
    public string $country = 'CD';
    public string $currency = 'CDF';
    public string $timezone = 'Africa/Lubumbashi';

    public string $inviteEmail = '';
    public string $inviteRole = 'commercial';

    public function switchTo(int $venueId, VenueSwitcher $switcher)
    {
        try {
            $switcher->switchTo(Auth::user(), Venue::findOrFail($venueId));
        } catch (\DomainException $e) {
            $this->addError('switch', $e->getMessage());

            return;
        }

        return $this->redirect(route('dashboard'), navigate: true);
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

        $provisioner->create(Auth::user(), $data + ['city' => $this->city ?: null]);

        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function invite(MembershipService $memberships): void
    {
        $data = $this->validate([
            'inviteEmail' => ['required', 'email', 'max:255'],
            'inviteRole' => ['required', Rule::in(['proprietaire', 'commercial', 'technique'])],
        ]);

        $memberships->invite(Auth::user()->venue, Auth::user(), $data['inviteEmail'], Role::from($data['inviteRole']));

        $this->reset('inviteEmail');
        session()->flash('success', 'Invitation envoyée. Le membre reçoit un lien pour définir son mot de passe.');
    }

    public function removeMember(int $userId, MembershipService $memberships): void
    {
        try {
            $memberships->remove(Auth::user()->venue, Auth::user(), $userId);
        } catch (\DomainException $e) {
            $this->addError('team', $e->getMessage());
        }
    }

    public function render()
    {
        $user = Auth::user();
        $current = $user->venue;

        $isOwner = $current
            && $current->members()->whereKey($user->id)->wherePivot('role', Role::Owner->value)->exists();

        return view('livewire.venues.my-venues', [
            'venues' => $user->venues()->orderBy('name')->get(),
            'current' => $current,
            'team' => $current ? $current->members()->orderBy('name')->get() : collect(),
            'isOwner' => $isOwner,
            'roles' => Role::cases(),
            'currencies' => config('venue.currencies'),
        ])->layout('layouts.app');
    }
}
<?php

namespace App\Livewire\Api;

use App\Enums\Role;
use App\Support\ApiAbilities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ApiTokens extends Component
{
    public string $name = '';
    public array $abilities = [];
    public ?string $plainToken = null;

    public function mount(): void
    {
        abort_unless(Auth::user()->role === Role::Owner, 403);
    }

    public function preset(string $preset): void
    {
        $this->abilities = match ($preset) {
            'door' => [ApiAbilities::GUESTS_READ, ApiAbilities::CHECKINS_WRITE],
            'reporting' => [ApiAbilities::SPACES_READ, ApiAbilities::BOOKINGS_READ, ApiAbilities::AVAILABILITY_READ],
            default => [],
        };
    }

    public function create(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:80'],
            'abilities' => ['required', 'array', 'min:1'],
            'abilities.*' => [Rule::in(ApiAbilities::ALL)],
        ]);

        $user = Auth::user();
        $token = $user->createToken($this->name, $this->abilities);
        $token->accessToken->forceFill(['venue_id' => $user->venue_id])->save();

        // Le jeton en clair n'est affiché qu'une seule fois
        $this->plainToken = $token->plainTextToken;
        $this->reset('name');
        $this->abilities = [];
    }

    public function revoke(int $id): void
    {
        Auth::user()->tokens()
            ->where('venue_id', Auth::user()->venue_id)
            ->whereKey($id)
            ->delete();
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.api.api-tokens', [
            'tokens' => $user->tokens()->where('venue_id', $user->venue_id)->latest('id')->get(),
            'labels' => ApiAbilities::LABELS,
        ])->layout('layouts.app');
    }
}
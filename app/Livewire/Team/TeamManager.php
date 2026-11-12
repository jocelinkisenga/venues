<?php

namespace App\Livewire\Team;

use App\Enums\Role;
use App\Services\MembershipService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TeamManager extends Component
{
    // Formulaire d'ajout
    public string $name = '';
    public string $email = '';
    public string $role = 'commercial';
    public string $mode = 'invite';

    // Résultat d'un ajout avec mot de passe provisoire (affiché une seule fois)
    public ?string $temporaryPassword = null;
    public ?string $temporaryFor = null;

    // Rôle de chaque membre, indexé par identifiant
    public array $memberRoles = [];

    public ?string $notice = null;

    public function mount(MembershipService $team): void
    {
        $team->assertOwner(Auth::user()->venue, Auth::user());
        $this->syncRoles();
    }

    public function add(MembershipService $team): void
    {
        $this->notice = null;

        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::in($this->roleValues())],
            'mode' => ['required', Rule::in(['invite', 'password'])],
        ]);

        $venue = Auth::user()->venue;
        $role = Role::from($data['role']);

        try {
            if ($data['mode'] === 'invite') {
                $user = $team->inviteByEmail($venue, Auth::user(), $data['name'], $data['email'], $role);
                $this->notice = "Invitation envoyée à {$user->email}.";
            } else {
                $this->temporaryPassword = $team->createWithTemporaryPassword(
                    $venue, Auth::user(), $data['name'], $data['email'], $role,
                );
                $this->temporaryFor = Str::lower(trim($data['email']));
                $this->notice = "Compte créé pour {$this->temporaryFor}.";
            }
        } catch (\DomainException $e) {
            $this->addError('email', $e->getMessage());

            return;
        }

        $this->reset('name', 'email');
        $this->role = 'commercial';
        $this->syncRoles();
    }

    /** Déclenché lorsque le propriétaire change le rôle d'un membre dans la liste. */
    public function updatedMemberRoles($value, $key): void
    {
        $this->notice = null;

        $venue = Auth::user()->venue;
        $userId = (int) $key;
        $previous = $venue->members()->whereKey($userId)->first()?->pivot->role;

        if (! in_array($value, $this->roleValues(), true)) {
            $this->memberRoles[$userId] = $previous;

            return;
        }

        try {
            app(MembershipService::class)->changeRole($venue, Auth::user(), $userId, Role::from($value));
            $this->notice = 'Rôle mis à jour.';
        } catch (\DomainException $e) {
            $this->memberRoles[$userId] = $previous;
            $this->addError('team', $e->getMessage());
        }
    }

    public function remove(int $userId, MembershipService $team): void
    {
        $this->notice = null;

        try {
            $team->remove(Auth::user()->venue, Auth::user(), $userId);
            $this->notice = 'Membre retiré de la salle.';
        } catch (\DomainException $e) {
            $this->addError('team', $e->getMessage());
        }

        $this->syncRoles();
    }

    public function dismissPassword(): void
    {
        $this->temporaryPassword = null;
        $this->temporaryFor = null;
    }

    public function render()
    {
        return view('livewire.team.team-manager', [
            'members' => Auth::user()->venue->members()->orderBy('name')->get(),
            'roleOptions' => Role::cases(),
            'me' => Auth::id(),
        ])->layout('layouts.app');
    }

    private function syncRoles(): void
    {
        $this->memberRoles = Auth::user()->venue->members()->get()
            ->mapWithKeys(fn ($m) => [$m->id => $m->pivot->role])
            ->all();
    }

    private function roleValues(): array
    {
        return array_column(Role::cases(), 'value');
    }
}
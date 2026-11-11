<?php

namespace App\Livewire\Catalog;

use App\Enums\Role;
use App\Models\EventType;
use App\Models\Package;
use App\Models\Space;
use App\Services\CatalogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CatalogManager extends Component
{
    public const ZONES = [
        'grande_salle' => 'Grande salle', 'terrasse' => 'Terrasse', 'vip' => 'Espace VIP',
        'bar' => 'Bar', 'autre' => 'Autre',
    ];

    public const PACKAGE_TYPES = ['seche' => 'Location sèche', 'cle_en_main' => 'Clé en main'];

    public const TIME_TYPES = ['soiree' => 'Soirée / nuit', 'multi_jours' => 'Multi-jours'];

    public string $tab = 'spaces';

    public array $spaceForm = [];
    public array $typeForm = [];
    public array $packageForm = [];

    public function mount(): void
    {
        abort_unless(Auth::user()->role === Role::Owner, 403);

        $this->resetSpaceForm();
        $this->resetTypeForm();
        $this->resetPackageForm();
    }

    /* ---------- Espaces ---------- */

    public function editSpace(int $id): void
    {
        $space = $this->venueSpaces()->findOrFail($id);

        $this->spaceForm = [
            'id' => $space->id,
            'name' => $space->name,
            'capacity' => (string) $space->capacity,
            'zone' => $space->zone,
        ];
    }

    public function saveSpace(CatalogService $catalog): void
    {
        $data = $this->validate([
            'spaceForm.name' => ['required', 'string', 'max:120'],
            'spaceForm.capacity' => ['required', 'integer', 'min:1', 'max:5000'],
            'spaceForm.zone' => ['required', Rule::in(array_keys(self::ZONES))],
        ]);

        $values = $data['spaceForm'];

        if ($this->spaceForm['id']) {
            $catalog->updateSpace($this->venueSpaces()->findOrFail($this->spaceForm['id']), $values);
        } else {
            $catalog->addSpace(Auth::user()->venue, $values['name'], (int) $values['capacity'], $values['zone']);
        }

        $this->resetSpaceForm();
    }

    public function archiveSpace(int $id, CatalogService $catalog): void
    {
        $this->runSafely('spaces', fn () => $catalog->archiveSpace($this->venueSpaces()->findOrFail($id)));
    }

    public function restoreSpace(int $id, CatalogService $catalog): void
    {
        $catalog->restoreSpace($this->venueSpaces()->findOrFail($id));
    }

    public function resetSpaceForm(): void
    {
        $this->spaceForm = ['id' => null, 'name' => '', 'capacity' => '100', 'zone' => 'grande_salle'];
    }

    /* ---------- Types d'événements ---------- */

    public function editType(int $id): void
    {
        $type = $this->venueTypes()->findOrFail($id);

        $this->typeForm = ['id' => $type->id, 'name' => $type->name];
    }

    public function saveType(CatalogService $catalog): void
    {
        $venueId = Auth::user()->venue_id;
        $id = $this->typeForm['id'];

        $unique = Rule::unique('event_types', 'name')->where('venue_id', $venueId);

        $this->validate([
            'typeForm.name' => ['required', 'string', 'max:80', $id ? $unique->ignore($id) : $unique],
        ]);

        $name = $this->typeForm['name'];

        if ($id) {
            $catalog->renameEventType($this->venueTypes()->findOrFail($id), $name);
        } else {
            $catalog->addEventType(Auth::user()->venue, $name);
        }

        $this->resetTypeForm();
    }

    public function archiveType(int $id, CatalogService $catalog): void
    {
        $catalog->archiveEventType($this->venueTypes()->findOrFail($id));
    }

    public function restoreType(int $id, CatalogService $catalog): void
    {
        $catalog->restoreEventType($this->venueTypes()->findOrFail($id));
    }

    public function resetTypeForm(): void
    {
        $this->typeForm = ['id' => null, 'name' => ''];
    }

    /* ---------- Forfaits ---------- */

    public function editPackage(int $id): void
    {
        $package = $this->venuePackages()->with('spaces:id')->findOrFail($id);

        $this->packageForm = [
            'id' => $package->id,
            'name' => $package->name,
            'type' => $package->type,
            'time_type' => $package->time_type,
            'days' => (string) $package->days,
            'included_hours' => (string) $package->included_hours,
            'base_price' => (string) (float) $package->base_price,
            'overtime_rate' => (string) (float) $package->overtime_rate,
            'space_ids' => $package->spaces->pluck('id')->map(fn ($i) => (string) $i)->all(),
        ];
    }

    public function savePackage(CatalogService $catalog): void
    {
        $venueId = Auth::user()->venue_id;

        $data = $this->validate([
            'packageForm.name' => ['required', 'string', 'max:120'],
            'packageForm.type' => ['required', Rule::in(array_keys(self::PACKAGE_TYPES))],
            'packageForm.time_type' => ['required', Rule::in(array_keys(self::TIME_TYPES))],
            'packageForm.days' => ['required', 'integer', 'min:1', 'max:30'],
            'packageForm.included_hours' => ['required', 'integer', 'min:1', 'max:48'],
            'packageForm.base_price' => ['required', 'numeric', 'min:0'],
            'packageForm.overtime_rate' => ['required', 'numeric', 'min:0'],
            'packageForm.space_ids' => ['array'],
            'packageForm.space_ids.*' => [Rule::exists('spaces', 'id')->where('venue_id', $venueId)],
        ]);

        $f = $data['packageForm'];

        $attributes = [
            'name' => $f['name'],
            'type' => $f['type'],
            'time_type' => $f['time_type'],
            'days' => $f['days'],
            'included_hours' => $f['included_hours'],
            'base_price' => $f['base_price'],
            'overtime_rate' => $f['overtime_rate'],
        ];

        $spaceIds = array_map('intval', $f['space_ids'] ?? []);

        if ($this->packageForm['id']) {
            $catalog->updatePackage($this->venuePackages()->findOrFail($this->packageForm['id']), $attributes, $spaceIds);
        } else {
            $catalog->addPackage(Auth::user()->venue, $attributes, $spaceIds);
        }

        $this->resetPackageForm();
    }

    public function archivePackage(int $id, CatalogService $catalog): void
    {
        $catalog->archivePackage($this->venuePackages()->findOrFail($id));
    }

    public function restorePackage(int $id, CatalogService $catalog): void
    {
        $catalog->restorePackage($this->venuePackages()->findOrFail($id));
    }

    public function resetPackageForm(): void
    {
        $this->packageForm = [
            'id' => null, 'name' => '', 'type' => 'seche', 'time_type' => 'soiree',
            'days' => '1', 'included_hours' => '8', 'base_price' => '0', 'overtime_rate' => '0',
            'space_ids' => [],
        ];
    }

    /* ---------- Rendu ---------- */

    public function render()
    {
        return view('livewire.catalog.catalog-manager', [
            'spaces' => $this->venueSpaces()->orderBy('name')->get(),
            'types' => $this->venueTypes()->orderBy('name')->get(),
            'packages' => $this->venuePackages()->with('spaces:id,name')->orderBy('name')->get(),
            'allSpaces' => $this->venueSpaces()->active()->orderBy('name')->get(),
            'zones' => self::ZONES,
            'packageTypes' => self::PACKAGE_TYPES,
            'timeTypes' => self::TIME_TYPES,
            'venue' => Auth::user()->venue,
        ])->layout('layouts.app');
    }

    /* ---------- Helpers ---------- */

    private function venueSpaces()
    {
        return Space::where('venue_id', Auth::user()->venue_id);
    }

    private function venueTypes()
    {
        return EventType::where('venue_id', Auth::user()->venue_id);
    }

    private function venuePackages()
    {
        return Package::where('venue_id', Auth::user()->venue_id);
    }

    private function runSafely(string $errorKey, callable $action): void
    {
        try {
            $action();
        } catch (\DomainException $e) {
            $this->addError($errorKey, $e->getMessage());
        }
    }
}
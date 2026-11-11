<?php

namespace App\Livewire\Admin\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Providers extends Component
{
    use WithPagination;

    public string $search = '';

    public string $name = '';
    public string $slug = '';
    public string $driver = '';

    public bool $isEnabled = true;

    public ?int $editingId = null;

    public bool $showForm = false;

    public ?int $deletingId = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        // abort_unless(
        //     Auth::user()?->role === 'admin',
        //     403
        // );
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();

        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $provider = AiProvider::findOrFail($id);

        $this->editingId = $provider->id;

        $this->name = $provider->name;
        $this->slug = $provider->slug;
        $this->driver = $provider->driver;
        $this->isEnabled = $provider->is_enabled;

        $this->showForm = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('ai_providers', 'slug')
                    ->ignore($this->editingId),
            ],

            'driver' => [
                'required',
                'string',
                'max:100',
            ],

            'isEnabled' => [
                'boolean',
            ],
        ]);

        $provider = $this->editingId
            ? AiProvider::findOrFail($this->editingId)
            : new AiProvider();

        $provider->name = $validated['name'];
        $provider->slug = $validated['slug'];
        $provider->driver = $validated['driver'];
        $provider->is_enabled = $validated['isEnabled'];

        $provider->save();

        session()->flash(
            'success',
            $this->editingId
                ? 'Provider updated successfully.'
                : 'Provider created successfully.'
        );

        $this->resetForm();
    }

    public function toggleStatus(int $id): void
    {
        $provider = AiProvider::findOrFail($id);

        $provider->update([
            'is_enabled' => ! $provider->is_enabled,
        ]);

        session()->flash(
            'success',
            $provider->is_enabled
                ? "{$provider->name} has been enabled."
                : "{$provider->name} has been disabled."
        );
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $provider = AiProvider::withCount('models')
            ->findOrFail($this->deletingId);

        if ($provider->models_count > 0) {
            session()->flash(
                'error',
                'This provider cannot be deleted because it still has AI models.'
            );

            $this->deletingId = null;

            return;
        }

        $provider->delete();

        session()->flash(
            'success',
            'Provider deleted successfully.'
        );

        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset([
            'name',
            'slug',
            'driver',
            'editingId',
        ]);

        $this->isEnabled = true;

        $this->showForm = false;

        $this->resetValidation();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.a-i.providers', [
            'providers' => AiProvider::query()
                ->withCount('models')
                ->when(
                    $this->search !== '',
                    function ($query) {
                        $search = '%' . $this->search . '%';

                        $query->where(function ($query) use ($search) {
                            $query
                                ->where('name', 'like', $search)
                                ->orWhere('slug', 'like', $search)
                                ->orWhere('driver', 'like', $search);
                        });
                    }
                )
                ->latest()
                ->paginate(10),
        ]);
    }
}
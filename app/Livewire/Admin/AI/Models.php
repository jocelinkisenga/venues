<?php

namespace App\Livewire\Admin\AI;

use App\Models\AiModel;
use App\Models\AiProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Models extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $providerId = null;

    public string $name = '';
    public string $modelIdentifier = '';
    public string $label = '';

    public bool $isEnabled = true;

    public int $priority = 100;

    public ?int $maxInputTokens = null;
    public ?int $maxOutputTokens = null;

    public ?string $inputPricePerMillion = null;
    public ?string $outputPricePerMillion = null;

    public ?int $editingId = null;

    public bool $showForm = false;

    public ?int $deletingId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'providerId' => ['except' => null],
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

    public function updatedProviderId(): void
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
        $model = AiModel::findOrFail($id);

        $this->editingId = $model->id;

        $this->providerId = $model->ai_provider_id;

        $this->name = $model->name;
        $this->modelIdentifier = $model->model_identifier;
        $this->label = $model->label ?? '';

        $this->isEnabled = $model->is_enabled;

        $this->priority = $model->priority;

        $this->maxInputTokens = $model->max_input_tokens;
        $this->maxOutputTokens = $model->max_output_tokens;

        $this->inputPricePerMillion =
            $model->input_price_per_million !== null
                ? (string) $model->input_price_per_million
                : null;

        $this->outputPricePerMillion =
            $model->output_price_per_million !== null
                ? (string) $model->output_price_per_million
                : null;

        $this->showForm = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'providerId' => [
                'required',
                'exists:ai_providers,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'modelIdentifier' => [
                'required',
                'string',
                'max:150',
            ],

            'label' => [
                'nullable',
                'string',
                'max:150',
            ],

            'isEnabled' => [
                'boolean',
            ],

            'priority' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],

            'maxInputTokens' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'maxOutputTokens' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'inputPricePerMillion' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'outputPricePerMillion' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        $provider = AiProvider::findOrFail(
            $validated['providerId']
        );

        if (! $provider->is_enabled) {
            $this->addError(
                'providerId',
                'The selected provider is disabled.'
            );

            return;
        }

        $model = $this->editingId
            ? AiModel::findOrFail($this->editingId)
            : new AiModel();

        $model->ai_provider_id =
            $validated['providerId'];

        $model->name =
            $validated['name'];

        $model->model_identifier =
            $validated['modelIdentifier'];

        $model->label =
            $validated['label'] ?: null;

        $model->is_enabled =
            $validated['isEnabled'];

        $model->priority =
            $validated['priority'];

        $model->max_input_tokens =
            $validated['maxInputTokens'];

        $model->max_output_tokens =
            $validated['maxOutputTokens'];

        $model->input_price_per_million =
            $validated['inputPricePerMillion'];

        $model->output_price_per_million =
            $validated['outputPricePerMillion'];

        $model->save();

        session()->flash(
            'success',
            $this->editingId
                ? 'AI model updated successfully.'
                : 'AI model created successfully.'
        );

        $this->resetForm();
    }

    public function toggleStatus(int $id): void
    {
        $model = AiModel::findOrFail($id);

        $model->update([
            'is_enabled' => ! $model->is_enabled,
        ]);

        session()->flash(
            'success',
            $model->is_enabled
                ? "{$model->name} has been enabled."
                : "{$model->name} has been disabled."
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

        $model = AiModel::findOrFail(
            $this->deletingId
        );

        $settings = \App\Models\AiSetting::current();

        /*
         * Ne pas supprimer le modèle actuellement utilisé
         * comme modèle primaire.
         */
        if ($settings->primary_model_id === $model->id) {
            session()->flash(
                'error',
                'This model is currently configured as the primary AI model.'
            );

            $this->deletingId = null;

            return;
        }

        /*
         * Ne pas supprimer un fallback sans d'abord
         * retirer sa référence de la configuration.
         */
        $fallbacks =
            $settings->fallback_model_ids ?? [];

        if (in_array($model->id, $fallbacks)) {
            session()->flash(
                'error',
                'This model is currently configured as a fallback model.'
            );

            $this->deletingId = null;

            return;
        }

        $model->delete();

        session()->flash(
            'success',
            'AI model deleted successfully.'
        );

        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset([
            'providerId',
            'name',
            'modelIdentifier',
            'label',
            'editingId',
            'maxInputTokens',
            'maxOutputTokens',
            'inputPricePerMillion',
            'outputPricePerMillion',
        ]);

        $this->isEnabled = true;
        $this->priority = 100;

        $this->showForm = false;

        $this->resetValidation();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.a-i.models', [
            'providers' => AiProvider::query()
                ->where('is_enabled', true)
                ->orderBy('name')
                ->get(),

            'models' => AiModel::query()
                ->with('provider')
                ->when(
                    $this->search !== '',
                    function ($query) {
                        $search = '%' . $this->search . '%';

                        $query->where(function ($query) use ($search) {
                            $query
                                ->where('name', 'like', $search)
                                ->orWhere(
                                    'model_identifier',
                                    'like',
                                    $search
                                )
                                ->orWhere(
                                    'label',
                                    'like',
                                    $search
                                )
                                ->orWhereHas(
                                    'provider',
                                    function ($query) use ($search) {
                                        $query->where(
                                            'name',
                                            'like',
                                            $search
                                        );
                                    }
                                );
                        });
                    }
                )
                ->when(
                    $this->providerId,
                    fn ($query) =>
                        $query->where(
                            'ai_provider_id',
                            $this->providerId
                        )
                )
                ->orderBy('priority')
                ->paginate(10),
        ]);
    }
}
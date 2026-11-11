<?php

namespace App\Livewire\Admin\AI;

use App\Models\AiModel;
use App\Models\AiSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Configuration extends Component
{
    public ?int $primaryModelId = null;

    public array $fallbackModelIds = [];

    public bool $fallbackEnabled = true;

    public bool $automaticFailover = true;

    public int $maxRetries = 2;

    public int $healthCooldownSeconds = 300;

    public function mount(): void
    {
      
      
        // abort_unless(
        //     auth()->user()->role === 'admin',
        //     403
        // );

        $settings = AiSetting::current();

        $this->primaryModelId = $settings->primary_model_id;

        $this->fallbackModelIds =
            $settings->fallback_model_ids ?? [];

        $this->fallbackEnabled =
            $settings->fallback_enabled;

        $this->automaticFailover =
            $settings->automatic_failover;

        $this->maxRetries =
            $settings->max_retries;

        $this->healthCooldownSeconds =
            $settings->health_cooldown_seconds;
    }

    public function save(): void
    {
        $this->validate([
            'primaryModelId' => [
                'nullable',
                'exists:ai_models,id',
            ],

            'fallbackModelIds' => [
                'array',
            ],

            'fallbackModelIds.*' => [
                'exists:ai_models,id',
            ],

            'maxRetries' => [
                'integer',
                'min:0',
                'max:5',
            ],

            'healthCooldownSeconds' => [
                'integer',
                'min:30',
                'max:86400',
            ],
        ]);

        if (
            $this->primaryModelId &&
            in_array(
                $this->primaryModelId,
                $this->fallbackModelIds
            )
        ) {
            $this->fallbackModelIds = array_values(
                array_filter(
                    $this->fallbackModelIds,
                    fn ($id) =>
                        $id != $this->primaryModelId
                )
            );
        }

        $settings = AiSetting::current();

        $settings->update([
            'primary_model_id' => $this->primaryModelId,
            'fallback_model_ids' => array_values(
                $this->fallbackModelIds
            ),
            'fallback_enabled' => $this->fallbackEnabled,
            'automatic_failover' => $this->automaticFailover,
            'max_retries' => $this->maxRetries,
            'health_cooldown_seconds' =>
                $this->healthCooldownSeconds,
            'updated_by' => Auth::id(),
        ]);

        session()->flash(
            'success',
            'Configuration IA enregistrée.'
        );
    }

    public function render()
    {
      
        return view('livewire.admin.ai.configuration', [
            'models' => AiModel::query()
                ->with('provider')
                ->where('is_enabled', true)
                ->whereHas('provider', function ($query) {
                    $query->where('is_enabled', true);
                })
                ->orderBy('priority')
                ->get(),
        ]);
    }
}
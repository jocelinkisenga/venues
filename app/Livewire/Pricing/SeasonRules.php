<?php

namespace App\Livewire\Pricing;

use App\Models\SeasonRule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SeasonRules extends Component
{
    public string $name = '';
    public string $starts_on = '';
    public string $ends_on = '';
    public string $percent = '10';

    public function mount(): void
    {
        abort_unless(Auth::user()->role->value === 'proprietaire', 403);
    }

    public function add(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:80'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'percent' => ['required', 'numeric', 'between:-90,200'],
        ]);

        SeasonRule::create($data + ['venue_id' => Auth::user()->venue_id]);

        $this->reset('name', 'starts_on', 'ends_on');
        $this->percent = '10';
    }

    public function remove(int $id): void
    {
        SeasonRule::where('venue_id', Auth::user()->venue_id)->findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.pricing.season-rules', [
            'rules' => SeasonRule::where('venue_id', Auth::user()->venue_id)->orderBy('starts_on')->get(),
        ])->layout('layouts.app');
    }
}
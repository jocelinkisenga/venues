<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan as ModelsPlan;
use Livewire\Component;

class Plan extends Component
{
    public $plans;

    public function mount() {
        $this->plans = ModelsPlan::all();
    }
    public function render()
    {
        return view('livewire.admin.plans.plan');
    }
}

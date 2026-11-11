<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Component;

class EditPlan extends Component
{
    public string $name;
    public $price;
    public $tokens;
    public $description;
    public $plan;

    public function mount(int $planId) {
        $this->plan = Plan::find($planId);

        $this->name = $this->plan->name;
        $this->price = $this->plan->price;
        $this->description = $this->plan->description;
        $this->tokens = $this->plan->analysis_quota;
    }

        public function save()
    {
        
        $this->plan->update([
            'name' => $this->name,
            'price' => $this->price,
            'analysis_quota' => $this->tokens,
            'description' => $this->description
        ]);

        session()->flash('success', 'Plan updated successfully.');
        return redirect()->to('/admin/plans');
    }

    public function render()
    {
        return view('livewire.admin.plans.edit-plan');
    }
}

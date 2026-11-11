<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Component;

class CreatePlan extends Component
{
    public string $name;
    public $price;
    public $tokens;
    public $description;

        protected function rules()
    {
        $rules = [
            'name' => 'required|min:3',
            'price' => 'required',
            'tokens' => 'required',
            'description' => 'nullable',
        ];


        return $rules;
    }

        public function save()
    {
        $this->validate();


        Plan::create([
            'name' => $this->name,
            'price' => $this->price,
            'analysis_quota' => $this->tokens,
            'description' => $this->description,
            "slug" => $this->name
        ]);

        session()->flash('success', 'Plan created successfully.');
        return redirect()->to('/admin/plans');
    }

    public function render()
    {
        return view('livewire.admin.plans.create-plan');
    }
}

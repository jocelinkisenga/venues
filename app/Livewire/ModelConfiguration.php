<?php

namespace App\Livewire;

use Livewire\Component;

class ModelConfiguration extends Component
{
    public $models;

    public function mount(){
        $this->models = ModelConfiguration::all();
    }
    public function render()
    {
        return view('livewire.model-configuration');
    }
}

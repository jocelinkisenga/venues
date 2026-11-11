<?php

namespace App\Livewire;

use App\Services\TaxProfileService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Nnjeim\World\World;
use Livewire\Attributes\Validate;
use App\Models\Content;
use Livewire\Attributes\Layout;



class Blog extends Component
{
public $articles = [];

    
    public function render()
    { 
        // $this->articles = Content::where("type" ,"=", "blog")->get();
        // return view('livewire.blog');
    }
}

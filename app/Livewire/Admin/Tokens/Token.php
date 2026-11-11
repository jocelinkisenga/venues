<?php

namespace App\Livewire\Admin\Tokens;

use App\Models\Token as ModelsToken;
use Livewire\Component;

class Token extends Component
{
    public $tokens;

    public function mount() {
        $this->tokens = ModelsToken::latest()->get();
    }
    public function render()
    {
        return view('livewire.admin.tokens.token');
    }
}

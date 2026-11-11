<?php

namespace App\Livewire\Admin\Tokens;

use App\Actions\IncrementTokens;
use App\Models\Token;
use Livewire\Component;

class CreateToken extends Component
{

    public string $supplier;
    public $price;
    public $tokens;

        protected function rules()
    {
        $rules = [
            'supplier' => 'required|min:3',
            'price' => 'required',
            'tokens' => 'required',
        ];


        return $rules;
    }

        public function save(IncrementTokens $incrementTokens)
    {
        $this->validate();

        $lastTokens = Token::latest('id')->value('total_tokens') ?? 0 ;
        
        Token::create([
            'entry_tokens' => $this->tokens,
            'price' => $this->price,
            'supplier' => $this->supplier,
            'total_tokens' => $lastTokens + (int) $this->tokens
        ]);

       // $incrementTokens->handler($this->tokens);

        session()->flash('success', 'Plan created successfully.');
        return redirect()->to('/admin/tokens');
    }

    public function render()
    {
        return view('livewire.admin.tokens.create-token');
    }
}

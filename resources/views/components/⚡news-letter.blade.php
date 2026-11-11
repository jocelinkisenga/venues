<?php

use Livewire\Component;
use App\Models\NewsLetter;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

new class extends Component
{

    #[Validate('required', message: 'The email field cannot be empty.')]
    #[Validate('unique:news_letters', message : 'The email already exist')]
    public $email = "";

    public ?boolean $subscribed;

    public function save() {
        $this->validate();

        NewsLetter::create([
            'email' => $this->email]);
        $this->subscribed = true;
    }
};
?>

<div>
     <div  class="pt-2">
    
                    <div class="flex flex-col sm:flex-row gap-2 max-w-md">
                        <input wire:model.live="email" type="email" 
                               name="email" 
                               required 
                               placeholder="Enter your email" 
                               class="bg-slate-50 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-600 dark:focus:border-blue-500 transition flex-1">
                        
                        <button  wire:click="save"
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold text-sm px-6 py-3 rounded-xl transition shadow-sm whitespace-nowrap">
                            Subscribe
                        </button>
                    </div>
                    @error("email")
                    <span class="block text-xs text-red-400 dark:text-red-500 mt-2">
                        {{$message}}
                    </span>
                    @enderror
                </div>
</div>
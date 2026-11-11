<?php

namespace App\Livewire\User\Library;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.client')]
class SavedContent extends Component
{
    public function render()
    {
        return view(
            'livewire.user.library.saved-content',
            [
                'contents' => auth()
                    ->user()
                    ->savedContents()
                    ->latest()
                    ->get()
            ]
        );
    }
}
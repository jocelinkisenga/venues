<?php

namespace App\Livewire\User\Library;

use App\Models\Content;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.client')]
class LibraryIndex extends Component
{
    public string $search = '';

    public string $type = '';

    public function render()
    {
        $contents = Content::query()

             ->when($this->search, function ($query) {

                $query->where('title', 'like', '%' . $this->search . '%');

            })

            ->when($this->type, function ($query) {

                $query->where('type', $this->type);

            })

            ->latest()

            ->get();

        return view(
            'livewire.user.library.library-index',
            [
                'contents' => $contents
            ]
        );
    }
}
<?php

namespace App\Livewire\User\Library;

use Livewire\Attributes\Layout;
use App\Models\Content;
use Livewire\Component;

#[Layout('layouts.client')]
class ContentShow extends Component
{

public bool $isSaved = false;
    public Content $content;

    public $relatedContents;

    public function mount($slug)
{
    $user = auth()->user();

    $this->content = Content::where(
        'slug',
        $slug
    )
    ->firstOrFail();

    if (!$user->hasAccessTo($this->content)) {

        abort(403);

    }

    $this->isSaved = $user->savedContents()
        ->where('content_id', $this->content->id)
        ->exists();

    $this->relatedContents = Content::where(
        'id',
        '!=',
        $this->content->id
    )
    ->where('type', $this->content->type)
    ->where('status', 'published')
    ->latest()
    ->take(3)
    ->get();
}
    
    public function toggleSave()
{
    $user = auth()->user();

    if ($this->isSaved) {

        $user->savedContents()
            ->detach($this->content->id);

        $this->isSaved = false;

    } else {

        $user->savedContents()
            ->attach($this->content->id);

        $this->isSaved = true;
    }
}

    public function render()
    {
        return view(
            'livewire.user.library.content-show'
        );
    }
}
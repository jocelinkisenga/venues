<?php
namespace App\Livewire\Admin\Content;

use App\Models\Content;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ContentIndex extends Component
{
    use WithPagination;

    // #[Url] permet de garder les filtres dans l'URL (pratique pour le partage de lien)
    #[Url]
    public $search = '';

    #[Url]
    public $filterType = '';

    // Réinitialise la pagination à 1 dès qu'un filtre change
    public function updating($property)
    {
        if (in_array($property, ['search', 'filterType'])) {
            $this->resetPage();
        }
    }

    public function togglePublish($contentId)
    {
        $content = Content::findOrFail($contentId);
        
        // Inversion du statut
        $content->status = ($content->status === 'published') ? 'draft' : 'published';
        $content->save();

        // Feedback optionnel
        session()->flash('message', 'Le statut a été mis à jour.');
    }

    public function render()
    {
        $contents = Content::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.content.content-index', [
            'contents' => $contents
        ]);
    }
}

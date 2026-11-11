<?php

namespace App\Livewire;


use App\Models\DefaultQuestions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class DefaultMessage extends Component
{
    use WithPagination;

    public string $search = '';

    public string $message = '';

    public bool $isEnabled = true;

    public ?int $editingId = null;

    public bool $showForm = false;

    public ?int $deletingId = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        // abort_unless(
        //     Auth::user()?->role === 'admin',
        //     403
        // );
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();

        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $question = DefaultQuestions::findOrFail($id);

        $this->editingId = $question->id;

        $this->message = $question->message;
        $this->isEnabled = $question->is_anabled;

        $this->showForm = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'message' => [
                'required',
                'string',
                'max:100',
            ],
            'isEnabled' => [
                'boolean',
            ],
        ]);

        $question = $this->editingId
            ? DefaultQuestions::findOrFail($this->editingId)
            : new DefaultQuestions();

        $question->message = $validated['message'];
        $question->is_anabled = $validated['isEnabled'];

        $question->save();

        session()->flash(
            'success',
            $this->editingId
                ? 'question updated successfully.'
                : 'question created successfully.'
        );

        $this->resetForm();
    }

    public function toggleStatus(int $id): void
    {
        $question = DefaultQuestions::findOrFail($id);

        $question->update([
            'is_anabled' => ! $question->is_enabled,
        ]);

        session()->flash(
            'success',
            $question->is_enabled
                ? "question has been enabled."
                : "question has been disabled."
        );
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $question = DefaultQuestions::findOrFail($this->deletingId);


        $question->delete();

        session()->flash(
            'success',
            'question deleted successfully.'
        );

        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset([
            'message'
        ]);

        $this->isEnabled = true;

        $this->showForm = false;

        $this->resetValidation();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.default-message', [
            'questions' => DefaultQuestions::query()
                ->when(
                    $this->search !== '',
                    function ($query) {
                        $search = '%' . $this->search . '%';

                        $query->where(function ($query) use ($search) {
                            $query
                                ->where('message', 'like', $search);
                        });
                    }
                )
                ->latest()
                ->paginate(10),
        ]);
    }
}
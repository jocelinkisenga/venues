<?php

namespace App\Livewire\Operations;

use App\Models\Booking;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChecklistRunner extends Component
{
    public Booking $booking;
    public string $phase;

    public function mount(Booking $booking, string $phase): void
    {
        abort_unless(
            $booking->venue_id === Auth::user()->venue_id && in_array($phase, ['entry', 'exit'], true),
            404
        );

        $this->booking = $booking;
        $this->phase = $phase;
    }

    private function checklist(): Checklist
    {
        return Checklist::firstOrCreate(
            ['booking_id' => $this->booking->id, 'phase' => $this->phase],
            ['items' => collect(config("venue.checklists.{$this->phase}"))
                ->map(fn ($label) => ['label' => $label, 'done' => false])
                ->values()
                ->all()],
        );
    }

    public function toggle(int $index): void
    {
        $checklist = $this->checklist();
        $items = $checklist->items;
        $items[$index]['done'] = ! $items[$index]['done'];

        $checklist->update([
            'items' => $items,
            'completed_by' => Auth::id(),
            'completed_at' => collect($items)->every(fn ($item) => $item['done']) ? now() : null,
        ]);
    }

    public function render()
    {
        return view('livewire.operations.checklist-runner', [
            'checklist' => $this->checklist(),
        ])->layout('layouts.app');
    }
}

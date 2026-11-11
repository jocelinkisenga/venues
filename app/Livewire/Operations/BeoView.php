<?php

namespace App\Livewire\Operations;

use App\Models\Booking;
use App\Services\BeoGenerator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BeoView extends Component
{
    public Booking $booking;

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        $this->booking = $booking;
    }

    public function render(BeoGenerator $generator)
    {
        return view('livewire.operations.beo', [
            'rows' => $generator->build($this->booking),
            'booking' => $this->booking->load(['space', 'venue', 'package']),
        ])->layout('layouts.app');
    }
}

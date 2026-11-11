<?php

namespace App\Livewire\Guests;

use App\Models\Booking;
use App\Models\Guest;
use App\Services\GuestService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckIn extends Component
{
    public Booking $booking;
    public string $code = '';
    public ?string $result = null;
    public ?string $resultType = null;

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);

        $this->booking = $booking;
    }

    /** Accepte le code saisi ou scanné : un lecteur USB envoie le code suivi d'Entrée. */
    public function admit(GuestService $guests): void
    {
        $this->validate(['code' => ['required', 'string', 'max:40']]);

        try {
            $guest = $guests->checkIn($this->booking, $this->code);
            $this->result = "Entrée validée : {$guest->full_name}".($guest->companions ? " (+{$guest->companions})" : '');
            $this->resultType = 'ok';
        } catch (\DomainException $e) {
            $this->result = $e->getMessage();
            $this->resultType = 'error';
        }

        $this->reset('code');
    }

    public function render()
    {
        $guests = Guest::where('booking_id', $this->booking->id)->get();

        return view('livewire.guests.check-in', [
            'expected' => $guests->where('rsvp_status', 'confirmed')->sum(fn (Guest $g) => $g->admittedCount()),
            'admitted' => $guests->whereNotNull('attended_at')->sum(fn (Guest $g) => $g->admittedCount()),
            'recent' => $guests->whereNotNull('attended_at')->sortByDesc('attended_at')->take(8),
        ])->layout('layouts.app');
    }
}
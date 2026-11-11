<?php

namespace App\Livewire\Guests;

use App\Models\Booking;
use App\Models\Guest;
use App\Services\GuestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class GuestList extends Component
{
    public Booking $booking;

    public string $newName = '';
    public string $newPhone = '';
    public string $bulk = '';

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        abort_unless(Auth::user()->role->canSell(), 403);

        $this->booking = $booking;
    }

    public function add(GuestService $guests): void
    {
        $this->validate([
            'newName' => ['required', 'string', 'max:120'],
            'newPhone' => ['nullable', 'string', 'max:30'],
        ]);

        $guests->create($this->booking, $this->newName, $this->newPhone ?: null);
        $this->reset('newName', 'newPhone');
    }

    /** Import rapide : une ligne par invité, au format « Nom ; téléphone ». */
    public function import(GuestService $guests): void
    {
        $lines = collect(preg_split('/\r\n|\r|\n/', $this->bulk))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->take(500);

        foreach ($lines as $line) {
            [$name, $phone] = array_pad(array_map('trim', explode(';', $line, 2)), 2, null);

            if ($name !== '') {
                $guests->create($this->booking, $name, $phone ?: null);
            }
        }

        $this->reset('bulk');
    }

    public function remove(int $guestId): void
    {
        Guest::where('booking_id', $this->booking->id)->findOrFail($guestId)->delete();
    }

    public function render()
    {
        $guests = Guest::where('booking_id', $this->booking->id)->orderBy('full_name')->get();

        $invited = $guests->map(fn (Guest $g) => [
            'guest' => $g,
            'whatsapp' => $g->phone
                ? 'https://wa.me/'.ltrim($g->phone, '+')
                    .'?text='.urlencode("Bonjour {$g->full_name}, merci de confirmer votre présence : ".route('rsvp.show', $g->token))
                : null,
        ]);

        return view('livewire.guests.guest-list', [
            'rows' => $invited,
            'confirmed' => $guests->where('rsvp_status', 'confirmed')->sum(fn (Guest $g) => $g->admittedCount()),
            'declined' => $guests->where('rsvp_status', 'declined')->count(),
            'pending' => $guests->where('rsvp_status', 'invited')->count(),
            'capacity' => $this->booking->guests_count,
        ])->layout('layouts.app');
    }
}
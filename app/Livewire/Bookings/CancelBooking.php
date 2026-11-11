<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Services\CancellationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CancelBooking extends Component
{
    public Booking $booking;
    public string $reason = '';
    public string $mode = 'refund';

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        abort_unless(Auth::user()->role->canSell(), 403);

        $this->booking = $booking;
    }

    public function cancel(CancellationService $service)
    {
        $this->validate([
            'reason' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'in:refund,credit_note,none'],
        ]);

        try {
            $service->cancel($this->booking, Auth::user(), $this->reason, $this->mode);
        } catch (\DomainException|\Stripe\Exception\ApiErrorException $e) {
            $this->addError('reason', $e->getMessage());

            return;
        }

        return $this->redirect(route('bookings.quote', $this->booking), navigate: true);
    }

    public function render(CancellationService $service)
    {
        return view('livewire.bookings.cancel-booking', [
            'preview' => $service->preview($this->booking),
        ])->layout('layouts.app');
    }
}
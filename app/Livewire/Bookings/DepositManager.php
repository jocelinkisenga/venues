<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Deposit;
use App\Services\DepositService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DepositManager extends Component
{
    public Booking $booking;
    public string $amount = '';
    public string $retained = '0';
    public string $reason = '';

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        abort_unless(Auth::user()->role->canSell(), 403);

        $this->booking = $booking;
    }

    public function hold(): void
    {
        $this->validate(['amount' => ['required', 'numeric', 'min:1']]);

        Deposit::create([
            'booking_id' => $this->booking->id,
            'amount' => $this->amount,
            'status' => 'held',
        ]);

        $this->reset('amount');
    }

    public function settle(DepositService $service): void
    {
        $deposit = Deposit::where('booking_id', $this->booking->id)
            ->where('status', 'held')
            ->latest('id')
            ->first();

        if (! $deposit) {
            $this->addError('retained', 'Aucune caution en cours.');

            return;
        }

        $this->validate([
            'retained' => ['required', 'numeric', 'min:0'],
            'reason' => ['required_unless:retained,0', 'string', 'max:255'],
        ]);

        $service->settle($deposit, Auth::user(), (float) $this->retained, $this->reason ?: null);

        $this->reset('reason');
        $this->retained = '0';
    }

    public function render()
    {
        $deposits = Deposit::where('booking_id', $this->booking->id)->latest('id')->get();

        return view('livewire.bookings.deposit-manager', [
            'deposits' => $deposits,
            'held' => $deposits->firstWhere('status', 'held'),
        ])->layout('layouts.app');
    }
}
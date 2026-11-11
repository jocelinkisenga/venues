<?php

namespace App\Livewire\Contracts;

use App\Models\Booking;
use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class ContractManager extends Component
{
    public Booking $booking;

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        abort_unless(Auth::user()->role->canSell(), 403);

        $this->booking = $booking;
    }

    public function issue(ContractService $service): void
    {
        try {
            $service->issue($this->booking);
        } catch (\DomainException $e) {
            $this->addError('contract', $e->getMessage());
        }
    }

    public function markSent(ContractService $service): void
    {
        $contract = Contract::where('booking_id', $this->booking->id)->firstOrFail();
        $service->markSent($contract);
    }

    public function render()
    {
        $contract = Contract::where('booking_id', $this->booking->id)->first();

        return view('livewire.contracts.contract-manager', [
            'contract' => $contract,
            'signUrl' => $contract
                ? URL::temporarySignedRoute('portal.contract', now()->addDays(30), ['contract' => $contract->id])
                : null,
        ])->layout('layouts.app');
    }
}
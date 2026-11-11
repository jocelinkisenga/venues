<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Contract;
use App\Models\Payment;
use App\Services\BeoGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function quote(Booking $booking)
    {
        $this->authorizeBooking($booking);
        abort_unless(Auth::user()->role->canSell(), 403);
        abort_unless($booking->quote, 404);

        $booking->load(['venue', 'space', 'quote.lines.partner', 'payments']);

        return Pdf::loadView('pdf.quote', [
            'booking' => $booking,
            'quote' => $booking->quote,
            'venue' => $booking->venue,
            'paid' => (float) $booking->payments->sum('amount'),
        ])->setPaper('a4')->download("devis-{$booking->reference}.pdf");
    }

    public function beo(Booking $booking, BeoGenerator $generator)
    {
        $this->authorizeBooking($booking);
        $booking->load(['venue', 'space']);

        return Pdf::loadView('pdf.beo', [
            'booking' => $booking,
            'venue' => $booking->venue,
            'rows' => $generator->build($booking),
        ])->setPaper('a4')->download("beo-{$booking->reference}.pdf");
    }

    public function receipt(Payment $payment)
    {
        $payment->load('booking.venue');
        abort_unless($payment->booking->venue_id === Auth::user()->venue_id, 404);
        abort_unless(Auth::user()->role->canSell(), 403);

        return Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'booking' => $payment->booking,
            'venue' => $payment->booking->venue,
        ])->setPaper('a5')->download("recu-{$payment->id}.pdf");
    }

    private function authorizeBooking(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
    }

    public function contract(Contract $contract)
{
    abort_unless($contract->venue_id === Auth::user()->venue_id, 404);
    abort_unless(Auth::user()->role->canSell(), 403);

    return Pdf::loadView('pdf.contract', ['contract' => $contract])
        ->setPaper('a4')
        ->download("contrat-{$contract->number}.pdf");
}
}

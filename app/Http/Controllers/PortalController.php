<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\CardPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PortalController extends Controller
{
    public function show(Booking $booking)
    {
        $booking->load(['venue', 'space', 'quote.lines']);

        $paid = (float) $booking->payments()->sum('amount');
        $total = (float) ($booking->quote?->total ?? 0);
        $deposit = round($total * $booking->venue->deposit_percent / 100);

        $payUrl = URL::temporarySignedRoute('portal.pay', now()->addDays(15), ['booking' => $booking->id]);

        return view('portal.show', compact('booking', 'paid', 'total', 'deposit', 'payUrl'));
    }

    public function pay(Request $request, Booking $booking, CardPaymentService $cards): RedirectResponse
    {
        $data = $request->validate(['kind' => ['required', 'in:deposit,balance']]);

        $booking->load(['venue', 'quote']);

        $paid = (float) $booking->payments()->sum('amount');
        $total = (float) ($booking->quote?->total ?? 0);
        $deposit = round($total * $booking->venue->deposit_percent / 100);

        $amount = $data['kind'] === 'deposit' ? $deposit - $paid : $total - $paid;

        if ($amount <= 0) {
            return back()->withErrors(['kind' => 'Aucun montant à payer pour le moment.']);
        }

        $label = $data['kind'] === 'deposit' ? 'Acompte' : 'Solde';

        return redirect()->away($cards->checkoutUrl($booking, $amount, $data['kind'], $label));
    }
}
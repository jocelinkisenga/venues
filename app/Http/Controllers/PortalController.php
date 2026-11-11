<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\CardPaymentService;
use App\Services\CinetPayGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    public function show(Booking $booking)
    {
        $booking->load(['venue', 'space', 'quote.lines']);

        $paid = (float) $booking->payments()->sum('amount');
        $total = (float) ($booking->quote?->total ?? 0);
        $deposit = round($total * $booking->venue->deposit_percent / 100);

        [$due, $kind] = $booking->status->value === 'option' && $paid < $deposit
            ? [$deposit - $paid, 'deposit']
            : [max(0, $total - $paid), 'balance'];

        $payUrl = URL::temporarySignedRoute('portal.pay', now()->addDays(15), ['booking' => $booking->id]);

        return view('portal.show', compact('booking', 'paid', 'total', 'deposit', 'payUrl', 'due', 'kind'));
    }

    public function pay(Request $request, Booking $booking, CardPaymentService $cards, CinetPayGateway $cinetpay): RedirectResponse
    {
        $data = $request->validate([
            'kind' => ['required', Rule::in(['deposit', 'balance'])],
            'method' => ['required', Rule::in(['card', 'mobile'])],
        ]);

        $booking->load(['venue', 'quote']);

        $paid = (float) $booking->payments()->sum('amount');
        $total = (float) ($booking->quote?->total ?? 0);
        $deposit = round($total * $booking->venue->deposit_percent / 100);

        $amount = $data['kind'] === 'deposit' ? $deposit - $paid : $total - $paid;

        if ($amount <= 0) {
            return back()->withErrors(['method' => 'Aucun montant à payer pour le moment.']);
        }

        $label = $data['kind'] === 'deposit' ? 'Acompte' : 'Solde';

        try {
            $url = $data['method'] === 'card'
                ? $cards->checkoutUrl($booking, $amount, $data['kind'], $label)
                : $cinetpay->initiate($booking, $amount, $data['kind'], $label);
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['method' => 'Le paiement est indisponible pour le moment. Réessayez ou contactez la salle.']);
        }

        return redirect()->away($url);
    }
}
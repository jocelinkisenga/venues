<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;

class PublicRequestController extends Controller
{
    /** Affichée uniquement à la personne qui vient d'envoyer la demande (lien signé). */
    public function thanks(Booking $booking): View
    {
        abort_unless($booking->source === 'online', 404);

        $booking->load('venue');

        $portalUrl = URL::temporarySignedRoute('portal.show', now()->addDays(15), ['booking' => $booking->id]);

        return view('public.thanks', compact('booking', 'portalUrl'));
    }
}
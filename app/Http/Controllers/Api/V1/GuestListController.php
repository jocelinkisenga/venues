<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Http\Request;

final class GuestListController extends Controller
{
    use ScopesToVenue;

    public function __invoke(Request $request, Booking $booking)
    {
        $venue = $this->venue($request);
        abort_unless($booking->venue_id === $venue->id, 404);

        $guests = Guest::where('booking_id', $booking->id)->orderBy('full_name')->get();

        return response()->json([
            'booking' => ['id' => $booking->id, 'reference' => $booking->reference],
            'data' => $guests->map(fn (Guest $g) => [
                'token' => $g->token,
                'full_name' => $g->full_name,
                'rsvp_status' => $g->rsvp_status,
                'companions' => $g->companions,
                'attended_at' => $g->attended_at?->toIso8601String(),
            ])->values(),
        ]);
    }
}
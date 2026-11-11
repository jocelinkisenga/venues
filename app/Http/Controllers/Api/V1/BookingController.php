<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

final class BookingController extends Controller
{
    use ScopesToVenue;

    public function __invoke(Request $request)
    {
        $venue = $this->venue($request);

        $data = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $bookings = Booking::where('venue_id', $venue->id)
            ->where('status', '!=', BookingStatus::Cancelled->value)
            ->where('starts_at', '<=', Carbon::parse($data['to'])->endOfDay()->toDateTimeString())
            ->where('ends_at', '>=', Carbon::parse($data['from'])->startOfDay()->toDateTimeString())
            ->with('space:id,name')
            ->orderBy('starts_at')
            ->limit(500)
            ->get();

        return response()->json(['data' => $bookings->map(fn (Booking $b) => [
            'id' => $b->id,
            'reference' => $b->reference,
            'event_type' => $b->event_type,
            'space' => $b->space?->name,
            'starts_at' => $b->starts_at->toIso8601String(),
            'ends_at' => $b->ends_at->toIso8601String(),
            'status' => $b->status->value,
            'guests_count' => $b->guests_count,
        ])->values()]);
    }
}
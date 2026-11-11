<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\OfflineCheckinService;
use Illuminate\Http\Request;

final class CheckinSyncController extends Controller
{
    use ScopesToVenue;

    public function __invoke(Request $request, OfflineCheckinService $service)
    {
        $venue = $this->venue($request);

        $data = $request->validate([
            'booking_id' => ['required', 'integer'],
            'scans' => ['required', 'array', 'max:500'],
            'scans.*.client_id' => ['required', 'uuid'],
            'scans.*.token' => ['required', 'string', 'max:40'],
            'scans.*.scanned_at' => ['required', 'date'],
        ]);

        $booking = Booking::where('venue_id', $venue->id)->findOrFail($data['booking_id']);

        return response()->json(['results' => $service->apply($booking, $data['scans'])]);
    }
}
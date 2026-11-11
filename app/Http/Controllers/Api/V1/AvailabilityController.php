<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Space;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class AvailabilityController extends Controller
{
    use ScopesToVenue;

    public function __invoke(Request $request, AvailabilityService $availability)
    {
        $venue = $this->venue($request);

        $data = $request->validate([
            'space_id' => ['required', Rule::exists('spaces', 'id')->where('venue_id', $venue->id)],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        $space = Space::where('venue_id', $venue->id)->findOrFail($data['space_id']);

        $free = $availability->conflicts(
            $space,
            Carbon::parse($data['starts_at']),
            Carbon::parse($data['ends_at']),
        )->isEmpty();

        return response()->json(['available' => $free]);
    }
}
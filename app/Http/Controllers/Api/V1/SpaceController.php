<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Http\Request;

final class SpaceController extends Controller
{
    use ScopesToVenue;

    public function __invoke(Request $request)
    {
        $venue = $this->venue($request);

        return response()->json([
            'data' => Space::where('venue_id', $venue->id)->active()->orderBy('name')
                ->get(['id', 'name', 'zone', 'capacity']),
        ]);
    }
}
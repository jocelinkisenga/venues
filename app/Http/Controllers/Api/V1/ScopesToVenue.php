<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Venue;
use Illuminate\Http\Request;

trait ScopesToVenue
{
    protected function venue(Request $request): Venue
    {
        $token = $request->user()?->currentAccessToken();

        abort_unless($token && $token->venue_id, 403, 'Jeton non lié à une salle.');

        $venue = Venue::findOrFail($token->venue_id);

        abort_unless($request->user()->venues()->whereKey($venue->id)->exists(), 403);
        abort_if($venue->isSuspended(), 403, 'Salle suspendue.');

        return $venue;
    }
}
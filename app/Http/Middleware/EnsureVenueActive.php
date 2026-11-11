<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVenueActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $venue = $user?->venue;

        if ($user && $venue?->isSuspended() && ! $user->is_super_admin) {
            return response()->view('errors.suspended', [], 403);
        }

        return $next($request);
    }
}
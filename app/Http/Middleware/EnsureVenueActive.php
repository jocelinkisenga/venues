<?php

namespace App\Http\Middleware;

use App\Services\VenueSwitcher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVenueActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Pas de salle active : on ouvre la première salle de l'utilisateur, si elle existe
        if (! $user->venue_id) {
            $first = $user->venues()->orderBy('name')->first();

            if ($first) {
                try {
                    app(VenueSwitcher::class)->switchTo($user, $first);
                } catch (\DomainException) {
                    return response()->view('errors.suspended', [], 403);
                }

                $user->refresh();
            } elseif ($user->is_super_admin) {
                return redirect()->route('platform');
            } else {
                return redirect()->route('onboarding');
            }
        }

        if ($user->venue?->isSuspended() && ! $user->is_super_admin) {
            return response()->view('errors.suspended', [], 403);
        }

        return $next($request);
    }
}
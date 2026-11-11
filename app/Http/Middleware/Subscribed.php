<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
	    /**
     * Handle an incoming request.
     */
public function handle(Request $request, Closure $next)
{
    $user = $request->user();

    // 1. Si l'utilisateur n'est pas connecté, 
    
    if (!$user) {
        return $next($request);
    }

    if ($user->subscribed('default')) {
        return redirect()->route('subscription.upgrade')
            ->with('status', 'Vous avez déjà un abonnement actif.');
    }

    
    return $next($request);
}
}
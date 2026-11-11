<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubscriptionMiddleWare
{
public function handle(Request $request, Closure $next)
{
    $user = $request->user();


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
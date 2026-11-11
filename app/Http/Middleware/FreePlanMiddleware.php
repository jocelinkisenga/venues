<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FreePlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->plan->value === "free") {
 
                return redirect()
                    ->route("pricing")
                    ->with("error", "You must have an active subscription.");         
        }
        return $next($request);
       
    }
}

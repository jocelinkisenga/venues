<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $content = $request->route('slug');

        if (!$user) {
            return redirect()->route('login');
        }

        // simple fallback (tu amélioreras avec DB check dans Content model)
        if ($user->plan === 'free') {

            // tu peux bloquer premium content ici plus tard
        }

        return $next($request);
    }
}
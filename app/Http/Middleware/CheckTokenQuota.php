<?php

namespace App\Http\Middleware;

use App\Enums\FreeTokensPlan;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Plan; 

class CheckTokenQuota
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // 1. GESTION DU PLAN GRATUIT
        if ($user->plan->value === "free") {
 
            $usedFreeTokens = $user->ailogs()->sum("tokens_used");

            if ($usedFreeTokens >= FreeTokensPlan::FREE->value) { 
                return redirect()
                    ->route("pricing")
                    ->with("error", "You must have an active subscription.");
            }

           
            return $next($request);
        }

        // 2. GESTION DU PLAN PAYANT
        $subscription = $user->subscription("default");

        // Vérification de l'existence de la souscription avant de lire ses propriétés
        if (!$subscription || !$subscription->current_period_start || !$subscription->current_period_end) {
            return redirect()
                ->route("pricing")
                ->with("error", "Subscription period data is missing or inactive.");
        }

        $start = Carbon::parse($subscription->current_period_start);
        $end = Carbon::parse($subscription->current_period_end);

        $used = $user->ailogs()
            ->whereBetween("created_at", [$start, $end])
            ->sum("tokens_used");

        $plan = Plan::find($user->plan_id);

        if (!$plan) {
            return redirect()
                ->route("pricing")
                ->with("error", "Subscription plan details not found.");
        }

        if ($used >= $plan->analysis_quota) {
            return redirect()
                ->route("pricing")
                ->with("error", "Token quota exceeded for this billing cycle.");
        }

        return $next($request);
    }
}
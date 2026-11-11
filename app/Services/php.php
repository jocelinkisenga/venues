<?php 
namespace App\Services;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Mcp\Request;

class TokenService {

    protected $user;

public static function getTotalUserTokens()
{
    $user = Auth::user();

    if ($user->role->value !== 'client') {
        return 0;
    }

    if ($user->subscribed('default')) {
        $subscription = $user->subscription('default');

        return $user->ailogs()
            ->whereBetween('created_at', [
                $subscription->current_period_start,
                $subscription->current_period_end,
            ])
            ->sum('tokens_used');
    }

    // Utilisateur sans abonnement : total des tokens utilisés
    return $user->ailogs()->sum('tokens_used');
}

public static function totalPlanTokens() {
    return Plan::where("id",auth()->user()->plan_id)->first();

}

}
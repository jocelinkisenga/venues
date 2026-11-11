<?php
namespace App\Services;

use App\Enums\SubscriptionPlan;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionService {

	public function getStripePriceId(string $planPriceId){
		return config("services.stripe.prices.{$planPriceId}");
	}


	 /**
   * Centralisation : Met à jour le plan local de l'utilisateur (Pattern DRY)
   */

  public function syncLocalUserPlan($user, ?string $stripePriceId): void
  {
    if (!$stripePriceId) {
      return;
    }

    $subscription = $user->subscription("default");

    if (!$subscription) {
      return;
    }

    if ($stripePriceId === config("services.stripe.prices.pro")) {
      $plan = Plan::whereNameLike("pro")->first();

      $user->update([
        "plan" => SubscriptionPlan::PRO->value,
        "plan_id" => optional($plan)->id,
      ]);
    } elseif ($stripePriceId === config("services.stripe.prices.premium")) {
      $plan = Plan::whereNameLike("premium")->first();

      $user->update([
        "plan" => SubscriptionPlan::PREMIUM->value,
        "plan_id" => optional($plan)->id,
      ]);
    } else {
      return;
    }

    try {
      $stripeSubscription = $subscription->asStripeSubscription();

      $item = $stripeSubscription->items->data[0] ?? null;

      $subscription->update([
    'plan_id'              => optional($plan)->id,
    'current_period_start' => now(),
    'current_period_end'   => now()->addMonth(),
]);

//     $subscription->update([
//     'current_period_start' => $item ? Carbon::createFromTimestamp($item->current_period_start) : null,
//     'current_period_end'   => $item ? Carbon::createFromTimestamp($item->current_period_end)   : null,
    
// ]);


    } catch (\Throwable $e) {
      report($e);
      
      $subscription->update([
        "plan_id" => optional($plan)->id,
        "current_period_start" => now(),
        "current_period_end" => now()->addMonth(),
      ]);
    }
  }
}
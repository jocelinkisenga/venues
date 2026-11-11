<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookReceived;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StripeEventListener
{
  public function handle(WebhookReceived $event)
  {
    $payload = $event->payload;
    $type = $payload["type"] ?? "";

    // On cible uniquement les événements liés au cycle de l'abonnement
    if (
      in_array($type, [
        "customer.subscription.created",
        "customer.subscription.updated",
      ])
    ) {
      $stripeSubscription = $payload["data"]["object"];
      $stripeCustomerId = $stripeSubscription["customer"];
      $stripePriceId = $stripeSubscription["items"]["data"][0]["price"]["id"];

      // 1. Retrouver l'utilisateur via son identifiant Stripe
      $user = User::where("stripe_id", $stripeCustomerId)->first();

      if ($user) {
        // 2. Retrouver le Plan local correspondant au prix Stripe
        $plan = Plan::where("stripe_price_id", $stripePriceId)->first();

        if ($plan) {
          // 3. Mettre à jour la table 'users'
          $user->update([
            "plan" => $plan->name, // Si tu utilises un Enum, adapte ici
            "plan_id" => $plan->id,
          ]);
        }

        // 4. Mettre à jour la table 'subscriptions' avec les dates exactes
        $user
          ->subscriptions()
          ->where("stripe_id", $stripeSubscription["id"])
          ->update([
            "current_period_start" => Carbon::createFromTimestamp(
              $stripeSubscription["current_period_start"]
            ),
            "current_period_end" => Carbon::createFromTimestamp(
              $stripeSubscription["current_period_end"]
            ),
            "plan_id" => $plan ? $plan->id : null,
          ]);
      }
    }
  }
}

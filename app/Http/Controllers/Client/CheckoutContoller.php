<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\SubscriptionPlan;
class CheckoutContoller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $plan)
{
$subscription = $request->user()
    ->subscription('default');

if (
    $subscription &&
    $subscription->active()
) {

    return redirect()
        ->route('dashboard')
        ->with(
            'info',
            'Manage your subscription from your account settings.'
        );
}
    $plan = SubscriptionPlan::tryFrom($plan);

    if (!$plan) {
        abort(404);
    }

    // Free => pas de paiement Stripe
    if ($plan === SubscriptionPlan::FREE) {

        $request->user()->update([
            'plan' => SubscriptionPlan::FREE->value,
        ]);

        return redirect()->route('dashboard');
    }

    $priceId = config("services.stripe.prices.{$plan->value}");

    if (!$priceId) {
        abort(500, 'Plan non configuré.');
    }

    return $request->user()
        ->newSubscription('default', $priceId)
        ->checkout([
            'success_url' => route('checkout-success'),
            'cancel_url' => route('dashboard'),
        ]);
}

}

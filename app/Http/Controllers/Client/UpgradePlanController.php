<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SubscriptionService;

class UpgradePlanController extends Controller
{
  public function index(Request $request)
  {
    $user = $request->user();

    return view("client.subscription.upgrade", [
      "user" => $user,
      "currentPlan" => $user->plan,
    ]);
  }

  public function upgrade(Request $request, SubscriptionService $service)
  {
    $request->validate([
      "plan" => "required|in:pro,premium",
      "payment_method" => "required|string",
    ]);

    $user = $request->user();

    $priceId = $service->getStripePriceId($request->plan);
   
   
    if ($user->subscribed("default")) {
      $user->subscription("default")->swap($priceId);
    } else {
      $user
        ->newSubscription("default", $priceId)
        ->create($request->payment_method);
    }

    $user->refresh();

    $service->syncLocalUserPlan($user, $priceId);

    return redirect()
      ->route("upgrade.success")
      ->with("success", "Plan upgraded successfully!");
  }

  public function upgrade_success(Request $request, SubscriptionService $subscriptionService) {

        /** @var \App\Models\User $user */
    $user = $request->user();
    $subscription = $user->subscription("default");

    if (!$subscription || !$subscription->valid()) {
      return redirect()->route("dashboard");
    }

   
    $subscriptionService->syncLocalUserPlan($user, $subscription->stripe_price);

    return view("subscription.upgradeSuccess");

  }
}

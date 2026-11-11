<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Enums\SubscriptionPlan;
use Carbon\Carbon;
use App\Models\Plan;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
  /**
   * Affiche la page des tarifs.
   */
  public function pricing(): View
  {
    return view("pages.pricing");
  }

  /**
   * Affiche l'interface de paiement Stripe Elements embarquée.
   */
  public function showPaymentPage(Request $request): View
  {
    /** @var \App\Models\User $user */
    $user = $request->user();

    // Génération du SetupIntent indispensable pour l'Iframe Stripe Elements
    $intent = $user->createSetupIntent();

    return view("subscription.subscribe", [
      "intent" => $intent,
    ]);
  }

  /**
   * Traite la création de l'abonnement de manière dynamique et sécurisée.
   */
  public function processSubscription(Request $request, SubscriptionService $service): RedirectResponse
  {
    // Validation stricte des intrants
    $request->validate([
      "payment_method" => "required|string",
      "plan" => "required|in:pro,premium",
    ]);

    /** @var \App\Models\User $user */
    $user = $request->user();
    $planChosen = $request->plan;

    //test
//$results = User::whereTypeLike('pro')->get();

    $stripePriceId = config("services.stripe.prices.{$planChosen}");


    try {
      
      $user
        ->newSubscription("default", $stripePriceId)
        ->create($request->payment_method);

       $user->refresh();

      
      
      $service->syncLocalUserPlan($user, $stripePriceId);

      return redirect()->route("checkout-success")->with("success", "Plan upgraded successfully!");
    } catch (\Exception $e) {
      report($e); // Log l'erreur en interne
      return back()->withErrors([
        "error" => "Subscription failed: " . $e->getMessage(),
      ]);
    }
  }

  /**
   * Page de retour après succès (Mise à jour et sécurité).
   */
  public function success(Request $request, SubscriptionService $subscriptionService): RedirectResponse|View
  {
    /** @var \App\Models\User $user */
    $user = $request->user();
    $subscription = $user->subscription("default");

    if (!$subscription || !$subscription->valid()) {
      return redirect()->route("dashboard");
    }

   
    $subscriptionService->syncLocalUserPlan($user, $subscription->stripe_price);

    return view("subscription.success");
  }

  /**
   * Dashboard : Affiche l'état de l'abonnement, l'historique et la consommation.
   */
  public function subscription(Request $request): View
  {
    /** @var \App\Models\User $user */
    $user = $request->user();

    $subscription = $user->subscription("default");

    $cycleProgress = 0;
    $daysUsed = 0;
    $totalDays = 30;
    $daysRemaining = 0;
    $nextPaymentDate = null;

    if ($subscription && $subscription->valid()) {
      try {
        $stripeSubscription = $subscription->asStripeSubscription();

        $startTimestamp =
          $stripeSubscription->current_period_start ??
          $subscription->created_at->timestamp;
        $endTimestamp =
          $stripeSubscription->current_period_end ??
          $subscription->updated_at->addMonth()->timestamp;

        $start = Carbon::createFromTimestamp($startTimestamp);
        $end = Carbon::createFromTimestamp($endTimestamp);
      } catch (\Exception $e) {
        report($e);
        $start = $subscription->created_at ?? Carbon::now();
        $end = $subscription->ends_at ?? Carbon::now()->addMonth();
      }

      $totalDays = max(1, $start->diffInDays($end));
      $daysUsed = max(0, $start->diffInDays(Carbon::now()));
      $daysRemaining = max(0, Carbon::now()->diffInDays($end));

      $cycleProgress = min(100, round(($daysUsed / $totalDays) * 100));
      $nextPaymentDate = $end->format("d/m/Y");
    }

    $invoices = [];
    try {
      if ($user->hasStripeId()) {
        $invoices = $user->invoices();
      }
    } catch (\Exception $e) {
      report($e);
    }

    // Métriques de quotas
    $usageMetrics = [
      "label" => "Projets Ilands",
      "used" => $user->projects_count ?? 3,
      "total" => $subscription && $subscription->active() ? 50 : 5,
    ];
    $usageMetrics["percentage"] = min(
      100,
      round(($usageMetrics["used"] / $usageMetrics["total"]) * 100)
    );

    return view(
      "client.subscription.index",
      compact(
        "user",
        "subscription",
        "invoices",
        "cycleProgress",
        "daysUsed",
        "totalDays",
        "daysRemaining",
        "nextPaymentDate",
        "usageMetrics"
      )
    );
  }

  /**
   * Redirige vers le portail de facturation Stripe (Billing Portal).
   */
  public function billingPortal(Request $request): RedirectResponse
  {
    /** @var \App\Models\User $user */
    $user = $request->user();

    if (!$user->hasStripeId()) {
      return redirect()
        ->route("subscription.index")
        ->with("error", "Aucun profil de facturation trouvé.");
    }

    return $user->redirectToBillingPortal(route("pricing"));
  }

 
  /**
   * Force le téléchargement d'une facture spécifique au format PDF.
   */
  public function downloadInvoice(
    Request $request,
    string $invoiceId
  ): \Symfony\Component\HttpFoundation\Response {
    try {
      // Sécurité : findInvoiceOrFail s'assure que la facture appartient bien à l'utilisateur connecté
      return $request->user()->downloadInvoice($invoiceId, [
        "vendor" => config("app.name", "Ilands Corp"),
        "product" => "Abonnement Plateforme",
      ]);
    } catch (\Exception $e) {
      report($e);
      return back()->withErrors([
        "error" => "Impossible de récupérer cette facture.",
      ]);
    }
  }
}

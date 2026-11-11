<?php

namespace App\Services;

use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class StripeRevenueService
{
  /**
   * return monthly affaires
   */
  public function getMonthToDateRevenue(): float
  {
    return Cache::remember(
      "stripe_revenue_month_to_date",
      now()->addHours(4),
      function () {
        $stripe = Cashier::stripe();
        $revenue = 0;

        $charges = $stripe->charges->all([
          "created" => ["gte" => Carbon::now()->startOfMonth()->timestamp],
          "limit" => 100,
        ]);

        foreach ($charges->autoPagingIterator() as $charge) {
          if ($charge->status === "succeeded") {
            $amount = $charge->amount / 100;
            $refunded = ($charge->amount_refunded ?? 0) / 100;

            $revenue += $amount - $refunded;
          }
        }

        return $revenue;
      }
    );
  }

  /**
   * Returns all time affaires hystory.
   */
  public function getAllTimeRevenue(): float
  {
    return Cache::remember(
      "stripe_revenue_all_time",
      now()->addHours(12),
      function () {
        $stripe = Cashier::stripe();
        $revenue = 0;

        $charges = $stripe->charges->all(["limit" => 100]);

        foreach ($charges->autoPagingIterator() as $charge) {
          if ($charge->status === "succeeded") {
            $revenue +=
              ($charge->amount - ($charge->amount_refunded ?? 0)) / 100;
          }
        }

        return $revenue;
      }
    );
  }
}

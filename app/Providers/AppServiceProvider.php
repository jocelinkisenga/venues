<?php

namespace App\Providers;

use App\Listeners\Stripe\RecordCheckoutPayment;
use App\Models\Payer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Cashier::useCustomerModel(Payer::class);
        Event::listen(WebhookReceived::class, RecordCheckoutPayment::class);

        // 120 requêtes par minute et par jeton, ou par IP sans jeton
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)
            ->by(hash('sha256', $request->bearerToken() ?? $request->ip())));
    }
}
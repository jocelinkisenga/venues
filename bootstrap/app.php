<?php

use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\EnsureVenueActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureRole::class,
            'superadmin' => EnsureSuperAdmin::class,
            'venue.active' => EnsureVenueActive::class,
        ]);
        $middleware->validateCsrfTokens(except: ['stripe/*', 'cinetpay/*']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
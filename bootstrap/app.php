<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        //health: '/up', 
        health: '/status', 
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(prepend: [
            App\Http\Middleware\HandleViteRequests::class, 
        ]);

        $middleware->api(prepend: [
            App\Http\Middleware\AttachTokenToHeaders::class,
            App\Http\Middleware\EnsureTokenIsValid::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);

        $middleware->alias([
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

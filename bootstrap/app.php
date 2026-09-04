<?php

use App\Http\Middleware\DetectBrowserLocale;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\RememberAdminList;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'locale' => SetLocale::class,
        ]);

        // Mémorise la page de liste consultée dans l'administration.
        $middleware->web(append: [
            RememberAdminList::class,
            // Placé après SetLocale : il a besoin de connaître la langue en cours.
            DetectBrowserLocale::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

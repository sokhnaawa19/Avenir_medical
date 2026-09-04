<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Choisit la langue d'affichage.
 *
 * Le français est la langue par défaut : avenir-medic.com/boutique
 * L'anglais passe par un préfixe :        avenir-medic.com/en/boutique
 */
class SetLocale
{
    public function handle(Request $request, Closure $next, ?string $locale = null): Response
    {
        $locale = in_array($locale, config('app.locales', ['fr', 'en']), true)
            ? $locale
            : config('app.fallback_locale', 'fr');

        app()->setLocale($locale);

        // Les liens générés conservent automatiquement la langue.
        if ($locale !== config('app.fallback_locale', 'fr')) {
            URL::defaults(['locale' => $locale]);
        }

        return $next($request);
    }
}

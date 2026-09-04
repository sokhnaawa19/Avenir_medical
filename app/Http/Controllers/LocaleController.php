<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Enregistre la langue choisie par le visiteur.
 *
 * Ce choix est prioritaire sur la langue du navigateur : une fois
 * qu'il a cliqué sur FR ou EN, il n'est plus jamais redirigé.
 */
class LocaleController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, config('app.locales', []), true), 404);

        $destination = $request->string('retour')->toString();

        // Sécurité : on ne redirige que vers une page de notre site.
        if (blank($destination) || ! str_starts_with($destination, url('/'))) {
            $destination = url($locale === config('app.fallback_locale', 'fr') ? '/' : '/'.$locale);
        }

        return redirect()->to($destination)
            ->withCookie(cookie('langue', $locale, 60 * 24 * 365));
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Propose au visiteur la version du site correspondant à la langue
 * de son navigateur, lors de sa toute première visite.
 *
 * Garde-fous, dans l'ordre où ils s'appliquent :
 *
 *  1. La redirection n'a lieu QU'UNE FOIS. Un cookie mémorise ensuite
 *     la langue choisie : le visiteur n'est plus jamais redirigé.
 *  2. Les robots (Google, Bing…) ne sont jamais redirigés, sinon la
 *     version française ne serait plus référencée.
 *  3. Seules les pages publiques sont concernées : jamais
 *     l'administration, ni les formulaires, ni les fichiers.
 *  4. La redirection ne se déclenche que depuis la version française
 *     vers l'anglais, jamais l'inverse : aucune boucle possible.
 *  5. La fonction peut être désactivée depuis les réglages du site.
 */
class DetectBrowserLocale
{
    /** Durée de mémorisation du choix : un an. */
    private const COOKIE_DUREE = 60 * 24 * 365;

    private const COOKIE_NOM = 'langue';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->doitRediriger($request)) {
            return $next($request);
        }

        $langue = $this->langueDuNavigateur($request);
        $defaut = config('app.fallback_locale', 'fr');

        // Le navigateur est en français (ou dans une langue inconnue) :
        // on reste sur la version française, et on s'en souvient.
        if ($langue === $defaut) {
            return $next($request)->withCookie(
                cookie(self::COOKIE_NOM, $defaut, self::COOKIE_DUREE)
            );
        }

        // Navigateur en anglais : on l'emmène sur la version anglaise.
        $chemin = trim($request->path(), '/');
        $destination = url($langue.($chemin === '' || $chemin === '/' ? '' : '/'.$chemin));

        if ($requete = $request->getQueryString()) {
            $destination .= '?'.$requete;
        }

        return redirect()->to($destination)
            ->withCookie(cookie(self::COOKIE_NOM, $langue, self::COOKIE_DUREE));
    }

    /** Faut-il envisager une redirection ? */
    private function doitRediriger(Request $request): bool
    {
        // La fonction est-elle activée ?
        if (! settings()->boolean('auto_locale', false)) {
            return false;
        }

        // Uniquement l'affichage d'une page, jamais un envoi de formulaire.
        if (! $request->isMethod('GET')) {
            return false;
        }

        // Le visiteur a déjà une langue mémorisée : on la respecte.
        if ($request->cookie(self::COOKIE_NOM) !== null) {
            return false;
        }

        // Uniquement depuis la version française : pas de boucle possible.
        if (app()->getLocale() !== config('app.fallback_locale', 'fr')) {
            return false;
        }

        // Ni l'administration, ni les fichiers, ni les pages de connexion.
        foreach (['admin', 'langue', 'connexion', 'inscription', 'compte', 'storage', 'media', 'assets'] as $prefixe) {
            if ($request->is($prefixe) || $request->is($prefixe.'/*')) {
                return false;
            }
        }

        // Les robots d'indexation ne sont jamais redirigés.
        return ! $this->estUnRobot($request);
    }

    /**
     * La langue préférée du navigateur, si le site la propose.
     * Exemple d'en-tête reçu : « en-GB,en;q=0.9,fr;q=0.8 »
     */
    private function langueDuNavigateur(Request $request): string
    {
        $langues = config('app.locales', ['fr']);
        $defaut = config('app.fallback_locale', 'fr');

        $preference = $request->getPreferredLanguage($langues);

        if (! is_string($preference)) {
            return $defaut;
        }

        // « en_GB » ou « en-GB » deviennent « en »
        $code = strtolower(substr(str_replace('-', '_', $preference), 0, 2));

        return in_array($code, $langues, true) ? $code : $defaut;
    }

    private function estUnRobot(Request $request): bool
    {
        $agent = strtolower((string) $request->userAgent());

        if ($agent === '') {
            return true;
        }

        foreach ([
            'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
            'whatsapp', 'telegram', 'preview', 'lighthouse', 'pingdom',
        ] as $signature) {
            if (str_contains($agent, $signature)) {
                return true;
            }
        }

        return false;
    }
}

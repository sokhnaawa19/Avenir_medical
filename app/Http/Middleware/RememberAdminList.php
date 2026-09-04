<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mémorise la dernière page de liste consultée dans l'administration.
 *
 * Exemple : l'administrateur est sur « Produits, page 3, filtre Consommables ».
 * Il modifie ou supprime un produit : il revient exactement sur cette page,
 * et non sur la première.
 */
class RememberAdminList
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->estUnePageDeListe($request)) {
            $request->session()->put(
                self::cleDeSession($request->segment(2)),
                $request->fullUrl()
            );
        }

        return $next($request);
    }

    /**
     * Une page de liste est une adresse du type /admin/produits
     * (éventuellement avec ?page=3&q=…), mais pas /admin/produits/create
     * ni /admin/produits/xxx/edit.
     */
    private function estUnePageDeListe(Request $request): bool
    {
        return $request->isMethod('GET')
            && $request->segment(1) === 'admin'
            && filled($request->segment(2))
            && blank($request->segment(3));
    }

    public static function cleDeSession(?string $ressource): string
    {
        return 'admin.retour.'.($ressource ?: 'general');
    }
}

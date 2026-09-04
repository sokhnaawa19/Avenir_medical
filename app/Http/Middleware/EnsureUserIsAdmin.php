<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autorise l'acces a l'administration uniquement aux comptes administrateurs.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour continuer.');
        }

        if (! $user->isAdmin()) {
            abort(403, "Cette partie du site est reservee a l'equipe AVENIR MEDICAL.");
        }

        return $next($request);
    }
}

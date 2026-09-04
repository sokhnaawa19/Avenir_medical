<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Petit cache pour les contenus du site public.
 *
 * Les listes affichees sur l'accueil (produits, articles, domaines...)
 * sont gardees en memoire quelques minutes. Des qu'un contenu est modifie
 * dans l'administration, le cache est vide automatiquement.
 */
class SiteCache
{
    /** Duree de conservation, en secondes. */
    public const TTL = 600;

    /** Liste des elements mis en cache, pour pouvoir les vider. */
    private const KEYS = [
        'site.home',
    ];

    public static function remember(string $key, \Closure $callback): mixed
    {
        return Cache::remember($key, self::TTL, $callback);
    }

    public static function flush(): void
    {
        foreach (self::KEYS as $key) {
            Cache::forget($key);
        }
    }
}

<?php

use App\Services\CartService;
use App\Services\SettingsRepository;
use Illuminate\Support\Facades\Storage;

if (! function_exists('settings')) {
    /**
     * Acces au gestionnaire de reglages.
     */
    function settings(): SettingsRepository
    {
        return app(SettingsRepository::class);
    }
}

if (! function_exists('setting')) {
    /**
     * Valeur d'un reglage : setting('site_name').
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return settings()->get($key, $default);
    }
}

if (! function_exists('setting_image')) {
    /**
     * Adresse d'une image de reglage : setting_image('logo').
     */
    function setting_image(string $key, ?string $fallback = null): ?string
    {
        return settings()->image($key, $fallback);
    }
}

if (! function_exists('cart')) {
    /**
     * Acces au panier du visiteur.
     */
    function cart(): CartService
    {
        return app(CartService::class);
    }
}

if (! function_exists('money')) {
    /**
     * Affiche un prix : 45000 => "45 000 FCFA".
     */
    function money(int|float|null $amount, bool $withCurrency = true): string
    {
        $formatted = number_format((float) ($amount ?? 0), 0, ',', ' ');

        return $withCurrency
            ? $formatted.' '.setting('currency', 'FCFA')
            : $formatted;
    }
}

if (! function_exists('media')) {
    /**
     * Adresse d'un fichier envoye depuis l'administration.
     */
    function media(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('is_current')) {
    /**
     * Indique si la page affichee correspond a un motif de route.
     */
    function is_current(string ...$patterns): bool
    {
        return request()->routeIs(...$patterns);
    }
}

if (! function_exists('payment_methods')) {
    /**
     * Liste des moyens de paiement definis dans les reglages.
     *
     * @return array<int, string>
     */
    function payment_methods(): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) setting('payment_methods')) ?: [];

        return array_values(array_filter(array_map('trim', $lines)));
    }
}

if (! function_exists('video_embed')) {
    /**
     * Analyse un lien video et renvoie de quoi l'afficher.
     *
     * Accepte : YouTube, Vimeo, ou un fichier video (.mp4 / .webm)
     * envoye depuis l'administration.
     *
     * @return array{type: string, src: string, poster: ?string}|null
     */
    function video_embed(?string $url): ?array
    {
        if (blank($url)) {
            return null;
        }

        $url = trim($url);

        // YouTube : youtu.be/ID, watch?v=ID, /embed/ID, /shorts/ID
        if (preg_match('#(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{6,})#i', $url, $matches)) {
            return [
                'type' => 'youtube',
                'src' => 'https://www.youtube-nocookie.com/embed/'.$matches[1].'?autoplay=1&rel=0',
                'poster' => 'https://i.ytimg.com/vi/'.$matches[1].'/hqdefault.jpg',
                'mime' => null,
            ];
        }

        // Vimeo
        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $url, $matches)) {
            return [
                'type' => 'vimeo',
                'src' => 'https://player.vimeo.com/video/'.$matches[1].'?autoplay=1',
                'poster' => null,
                'mime' => null,
            ];
        }

        // Fichier video envoye depuis l'administration ou lien direct
        if (preg_match('#\.(mp4|m4v|webm|ogg|ogv|mov)(\?.*)?$#i', $url, $matches)) {
            $extension = strtolower($matches[1]);

            return [
                'type' => 'file',
                'src' => video_src($url),
                'poster' => null,
                'mime' => match ($extension) {
                    'webm' => 'video/webm',
                    'ogg', 'ogv' => 'video/ogg',
                    'mov' => 'video/quicktime',
                    default => 'video/mp4',
                },
            ];
        }

        return null;
    }
}

if (! function_exists('asset_v')) {
    /**
     * Adresse d'un fichier CSS ou JS, avec deux avantages :
     *
     * 1. la version allegee (.min) est utilisee si elle existe ;
     * 2. un numero de version est ajoute pour que les visiteurs
     *    recoivent tout de suite la nouvelle version apres une modification.
     */
    function asset_v(string $path): string
    {
        $minified = preg_replace('/\.(css|js)$/', '.min.$1', $path);

        if ($minified !== null && is_file(public_path($minified))) {
            $path = $minified;
        }

        $full = public_path($path);
        $version = is_file($full) ? filemtime($full) : null;

        return asset($path).($version ? '?v='.$version : '');
    }
}


if (! function_exists('video_src')) {
    /**
     * Adresse d'une vidéo enregistrée sur le site.
     *
     * En production, le fichier est servi directement par le serveur web
     * (plus rapide). En développement, il passe par la page /media/video
     * car le serveur de test de Laravel ne sait pas envoyer un fichier
     * par morceaux — sans cela, les vidéos ne démarrent pas.
     */
    function video_src(string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (app()->isProduction()) {
            return media($path) ?? $path;
        }

        return route('media.video', ['path' => $path]);
    }
}


if (! function_exists('admin_back')) {
    /**
     * Adresse de retour après un enregistrement ou une suppression.
     *
     * Renvoie la dernière page de liste consultée (avec sa pagination et
     * ses filtres). Si elle n'est pas connue, on retombe sur la route
     * indiquée en secours.
     */
    function admin_back(string $routeDeSecours, array $parametres = []): string
    {
        $ressource = request()->segment(2);
        $memorisee = session(\App\Http\Middleware\RememberAdminList::cleDeSession($ressource));

        // Sécurité : on ne redirige que vers une adresse de notre administration.
        if (is_string($memorisee) && str_starts_with($memorisee, url('/admin'))) {
            return $memorisee;
        }

        return route($routeDeSecours, $parametres);
    }
}


if (! function_exists('whatsapp_lines')) {
    /**
     * Les numéros WhatsApp renseignés, avec leur intitulé de service.
     *
     * @return array<int, array{label: string, number: string, link: string}>
     */
    function whatsapp_lines(): array
    {
        $lignes = [];

        foreach ([1, 2, 3] as $i) {
            $numero = (string) setting('whatsapp_'.$i.'_number');

            if (blank($numero)) {
                continue;
            }

            $lignes[] = [
                'label' => (string) setting('whatsapp_'.$i.'_label') ?: 'Nous écrire',
                'number' => $numero,
                'link' => 'https://wa.me/'.preg_replace('/\\D/', '', $numero),
            ];
        }

        // Aucun numéro par service : on retombe sur le numéro principal.
        if ($lignes === [] && filled(setting('whatsapp'))) {
            $numero = (string) setting('whatsapp');
            $lignes[] = [
                'label' => 'Nous écrire',
                'number' => $numero,
                'link' => 'https://wa.me/'.preg_replace('/\\D/', '', $numero),
            ];
        }

        return $lignes;
    }
}


if (! function_exists('lroute')) {
    /**
     * Un lien vers une page publique, dans la langue affichée.
     *
     * lroute('shop.index') renvoie /boutique en français
     * et /en/boutique en anglais.
     */
    function lroute(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $prefixe = app()->getLocale() === config('app.fallback_locale', 'fr') ? '' : app()->getLocale().'.';

        return route(
            \Illuminate\Support\Facades\Route::has($prefixe.$name) ? $prefixe.$name : $name,
            $parameters,
            $absolute
        );
    }
}

if (! function_exists('locale_url')) {
    /**
     * L'adresse de la page courante dans une autre langue.
     * Sert au sélecteur de langue et aux balises « hreflang ».
     */
    function locale_url(string $locale): string
    {
        $defaut = config('app.fallback_locale', 'fr');
        $chemin = trim(request()->path(), '/');

        // On retire le préfixe de langue éventuel.
        foreach (config('app.locales', []) as $code) {
            if ($code === $defaut) {
                continue;
            }

            if ($chemin === $code || str_starts_with($chemin, $code.'/')) {
                $chemin = trim(substr($chemin, strlen($code)), '/');
                break;
            }
        }

        $requete = request()->getQueryString();
        $suffixe = $requete ? '?'.$requete : '';

        if ($locale === $defaut) {
            return url($chemin === '' ? '/' : $chemin).$suffixe;
        }

        return url($locale.($chemin === '' ? '' : '/'.$chemin)).$suffixe;
    }
}

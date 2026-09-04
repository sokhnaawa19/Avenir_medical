<?php

return [

    'name' => env('APP_NAME', 'AVENIR MEDICAL'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => env('APP_TIMEZONE', 'Africa/Dakar'),

    'locale' => env('APP_LOCALE', 'fr'),

    /*
    | Les langues du site. Le français est la langue de référence,
    | l'anglais est accessible via le préfixe /en.
    */
    'locales' => ['fr', 'en'],

    'locale_names' => ['fr' => 'Français', 'en' => 'English'],

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'fr'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'fr_FR'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];

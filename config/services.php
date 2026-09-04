<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Traduction automatique du contenu
    |--------------------------------------------------------------------------
    | driver : deepl (recommandé), google ou claude
    | key    : la clé fournie par le service choisi
    | protected : les mots qui ne doivent JAMAIS être traduits
    */
    'translate' => [
        'driver' => env('TRANSLATE_DRIVER', 'deepl'),
        'key' => env('TRANSLATE_KEY'),
        'protected' => [
            'AVENIR MEDICAL', 'AVENIR PHARMA', 'AVENIR MEDICAL CONSULTING',
            'COMEN', 'SHINVA', 'CANON', 'RANDOX', 'SUNBIO', 'MINDRAY', 'TOSOH',
            'DRÄGER', 'FAGOR', 'EDAN', 'BOULE', 'ZENITHLAB', 'DIALIFE',
            'NITROCARE', 'SAIKANG MEDICAL', 'DISE', 'SANISWISS', 'ABRONN',
            'ULTRA CONTROLO', 'VILLA', 'GE HealthCare', 'B. Braun', 'Nihon Kohden',
            'Dakar', 'Thiès', 'Sénégal', 'Touba', 'Guédiawaye', 'Diamniadio',
            'WhatsApp', 'FCFA',
        ],
    ],

    // Emplacement reserve aux services externes (paiement, SMS, etc.).

];

<?php

use App\Models\Training;
use Illuminate\Database\Migrations\Migration;

/**
 * Rétablit les formations et leurs photos.
 *
 * Le défaut du bouton « Enregistrer » (formulaires imbriqués) a pu
 * supprimer des formations. Cette migration les recrée si elles
 * manquent, et rattache leurs photos si elles n'en ont plus.
 *
 * Une formation déjà présente n'est jamais modifiée : les textes
 * saisis depuis l'administration sont conservés.
 */
return new class extends Migration
{
    private array $formations = [
        [
            'title' => 'Imagerie médicale — formation CANON',
            'organism' => 'Canon Medical Systems',
            'description' => "Formation constructeur sur les équipements d'imagerie CANON : installation, réglages, contrôle qualité et maintenance.",
            'image' => 'formations/canon-dakar.webp',
            'photos' => ['formations/canon-dakar.webp'],
        ],
        [
            'title' => 'Blanchisserie hospitalière — formation FAGOR',
            'organism' => 'Fagor Professional',
            'description' => "Formation technique sur les équipements de blanchisserie hospitalière : mise en service, paramétrage et maintenance préventive.",
            'image' => 'formations/fagor-2.webp',
            'photos' => ['formations/fagor-1.webp', 'formations/fagor-2.webp', 'formations/fagor-3.webp'],
        ],
        [
            'title' => "Automates d'analyses — formation RANDOX",
            'organism' => 'Randox Laboratories',
            'country' => 'Inde',
            'city' => 'Bangalore',
            'participants' => 1,
            'description' => "Formation sur site chez le fabricant : maintenance et dépannage des automates d'analyses biologiques RANDOX.",
            'image' => 'formations/randox-3.webp',
            'photos' => ['formations/randox-3.webp', 'formations/randox-1.webp', 'formations/randox-2.webp'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->formations as $position => $donnees) {
            $photos = $donnees['photos'];
            unset($donnees['photos']);

            $formation = Training::query()->firstOrCreate(
                ['title' => $donnees['title']],
                array_merge($donnees, ['position' => $position, 'is_active' => true])
            );

            // Photo de couverture manquante : on la remet.
            if (blank($formation->getRawOriginal('image'))) {
                $formation->forceFill(['image' => $donnees['image']])->save();
            }

            // Galerie vide : on rattache les photos.
            if ($formation->photos()->count() === 0) {
                foreach ($photos as $index => $chemin) {
                    $formation->photos()->create(['image' => $chemin, 'position' => $index]);
                }
            }
        }
    }

    public function down(): void
    {
        // Rien à défaire.
    }
};

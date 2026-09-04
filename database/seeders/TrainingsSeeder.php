<?php

namespace Database\Seeders;

use App\Models\Training;
use Illuminate\Database\Seeder;

/**
 * Les formations suivies par les techniciens, avec leurs photos.
 * Les textes sont à valider et à compléter par l'entreprise.
 */
class TrainingsSeeder extends Seeder
{
    public function run(): void
    {
        $formations = [
            [
                'title' => 'Imagerie médicale — formation CANON',
                'organism' => 'Canon Medical Systems',
                'country' => null,
                'city' => null,
                'year' => null,
                'participants' => null,
                'description' => "Formation constructeur sur les équipements d'imagerie CANON : installation, réglages, contrôle qualité et maintenance.",
                'image' => 'formations/canon-dakar.webp',
                'photos' => ['formations/canon-dakar.webp'],
            ],
            [
                'title' => 'Blanchisserie hospitalière — formation FAGOR',
                'organism' => 'Fagor Professional',
                'country' => null,
                'city' => null,
                'year' => null,
                'participants' => null,
                'description' => "Formation technique sur les équipements de blanchisserie hospitalière : mise en service, paramétrage et maintenance préventive.",
                'image' => 'formations/fagor-2.webp',
                'photos' => ['formations/fagor-1.webp', 'formations/fagor-2.webp', 'formations/fagor-3.webp'],
            ],
            [
                'title' => "Automates d'analyses — formation RANDOX",
                'organism' => 'Randox Laboratories',
                'country' => 'Inde',
                'city' => 'Bangalore',
                'year' => null,
                'participants' => 1,
                'description' => "Formation sur site chez le fabricant : maintenance et dépannage des automates d'analyses biologiques RANDOX.",
                'image' => 'formations/randox-3.webp',
                'photos' => ['formations/randox-3.webp', 'formations/randox-1.webp', 'formations/randox-2.webp'],
            ],
        ];

        foreach ($formations as $position => $donnees) {
            $photos = $donnees['photos'];
            unset($donnees['photos']);

            $formation = Training::query()->firstOrCreate(
                ['title' => $donnees['title']],
                array_merge($donnees, ['position' => $position, 'is_active' => true])
            );

            // On n'ajoute les photos que la première fois.
            if ($formation->photos()->count() > 0) {
                continue;
            }

            foreach ($photos as $index => $chemin) {
                $formation->photos()->create(['image' => $chemin, 'position' => $index]);
            }
        }
    }
}

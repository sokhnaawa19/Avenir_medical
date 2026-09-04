<?php

use App\Models\Establishment;
use App\Models\Training;
use Illuminate\Database\Migrations\Migration;

/**
 * Installe les références et les formations fournies par l'entreprise.
 *
 * Ces contenus étaient jusqu'ici dans des « seeders », qu'il fallait
 * lancer séparément. Ils sont désormais posés par la migration :
 * un simple « php artisan migrate » suffit.
 *
 * Rien n'est écrasé : un établissement déjà enregistré est ignoré.
 */
return new class extends Migration
{
    /** [nom, type, ville, réalisation phare] */
    private array $etablissements = [
        // --- Institutions publiques ---
        ["Ministère de la Santé et de l'Hygiène Publique", 'Institution', 'Dakar', true],
        ['DEM — Direction des Équipements et de la Maintenance', 'Institution', 'Dakar', true],

        // --- Programmes et organisations ---
        ['REDISSE', 'Programme de santé', null, false],
        ['ENABEL', 'Coopération internationale', null, false],
        ['FNSS — Fondation Nationale Sénégal Solidaire', 'Fondation', 'Dakar', false],

        // --- Hôpitaux ---
        ['Hôpital de Pikine', 'Hôpital public', 'Pikine', true],
        ["IHS — Institut d'Hygiène Sociale", 'Établissement public', 'Dakar', false],
        ['Polyclinique de Dakar', 'Polyclinique', 'Dakar', false],
        ['Hôpital Abass Ndao', 'Hôpital public', 'Dakar', false],
        ['Hôpital HOGIP', 'Hôpital public', 'Guédiawaye', false],
        ['Hôpital Albert Royer', 'Hôpital pédiatrique', 'Dakar', false],
        ['Hôpital des Enfants de Diamniadio', 'Hôpital pédiatrique', 'Diamniadio', true],
        ['Hôpital Dalal Jamm', 'Hôpital public', 'Guédiawaye', false],
        ['Hôpital Cheikhoul Khadim de Touba', 'Hôpital public', 'Touba', true],
        ['Hôpital Ndamatou', 'Hôpital public', 'Touba', false],
        ['Hôpital Mame Abdou Aziz', 'Hôpital public', 'Touba', false],
        ['Hôpital Saint Jean de Dieu', 'Hôpital confessionnel', 'Thiès', false],

        // --- Cliniques ---
        ['Clinique Cheikhoul Khadim', 'Clinique privée', 'Touba', false],
        ['Santé 24', 'Clinique privée', 'Dakar', false],
        ['Clinique Cheikh Mouhamadou Bachir Mbacké', 'Clinique privée', 'Touba', false],

        // --- Centres de santé ---
        ['Centre de Santé des Maristes', 'Centre de santé', 'Dakar', false],
        ['Centre de Santé de Cambérène', 'Centre de santé', 'Dakar', false],
        ['Centre de Santé de Guédiawaye', 'Centre de santé', 'Guédiawaye', false],
        ['Centre de Santé de Keur Massar', 'Centre de santé', 'Keur Massar', false],
        ['Centre de Santé de Sangalkam', 'Centre de santé', 'Sangalkam', false],
        ['Centre de Santé de Popenguine', 'Centre de santé', 'Popenguine', false],
    ];

    /** Les formations, avec leurs photos. */
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
        foreach ($this->etablissements as $position => [$nom, $type, $ville, $phare]) {
            Establishment::query()->firstOrCreate(
                ['name' => $nom],
                [
                    'type' => $type,
                    'city' => $ville,
                    'country' => 'Sénégal',
                    'is_flagship' => $phare,
                    'is_featured' => true,
                    'is_active' => true,
                    'position' => $position,
                ]
            );
        }

        foreach ($this->formations as $position => $donnees) {
            $photos = $donnees['photos'];
            unset($donnees['photos']);

            $formation = Training::query()->firstOrCreate(
                ['title' => $donnees['title']],
                array_merge($donnees, ['position' => $position, 'is_active' => true])
            );

            if ($formation->photos()->count() > 0) {
                continue;
            }

            foreach ($photos as $index => $chemin) {
                $formation->photos()->create(['image' => $chemin, 'position' => $index]);
            }
        }
    }

    public function down(): void
    {
        // On ne supprime rien : le contenu a pu être modifié entre-temps.
    }
};

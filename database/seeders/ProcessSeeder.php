<?php

namespace Database\Seeders;

use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

/**
 * Les 5 étapes du parcours d'accompagnement.
 * Modifiables depuis l'administration (Parcours client).
 */
class ProcessSeeder extends Seeder
{
    public function run(): void
    {
        $etapes = [
            ['Comprendre', '🔍', 'Nous analysons votre besoin',
             'Vos contraintes, votre environnement et vos objectifs.'],
            ['Conseiller', '💡', 'Nous vous proposons la solution adaptée',
             'Équipements, configuration et recommandations techniques.'],
            ['Installer', '🔧', 'Nous mettons vos équipements en service',
             'Installation, configuration et vérifications sur site.'],
            ['Former', '🎓', 'Nous transmettons notre expertise',
             'Formation des utilisateurs et accompagnement des équipes.'],
            ['Maintenir', '🛡️', 'Nous assurons le suivi',
             'Maintenance préventive, corrective et assistance technique.'],
        ];

        foreach ($etapes as $position => [$titre, $icone, $sousTitre, $description]) {
            ProcessStep::query()->firstOrCreate(
                ['title' => $titre],
                [
                    'icon' => $icone,
                    'subtitle' => $sousTitre,
                    'description' => $description,
                    'position' => $position,
                    'is_active' => true,
                ]
            );
        }
    }
}

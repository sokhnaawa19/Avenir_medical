<?php

namespace Database\Seeders;

use App\Models\Establishment;
use Illuminate\Database\Seeder;

/**
 * Les établissements équipés par AVENIR MEDICAL.
 * Les logos pourront être ajoutés ensuite depuis l'administration.
 */
class ReferencesSeeder extends Seeder
{
    public function run(): void
    {
        // [nom, type, ville, réalisation phare]
        $etablissements = [
            // --- Institutions ---
            ["Ministère de la Santé et de l'Hygiène Publique", 'Institution', 'Dakar', true],
            ['DEM — Direction des Équipements et de la Maintenance', 'Institution', 'Dakar', true],

            // --- Organisations et programmes ---
            ['REDISSE', 'Programme de santé', null, false],
            ['ENABEL', 'Coopération internationale', null, false],
            ['FNSS — Fondation Nationale Sénégal Solidaire', 'Fondation', 'Dakar', false],

            // --- Hôpitaux ---
            ['Hôpital de Pikine', 'Hôpital public', 'Pikine', true],
            ["IHS — Institut d'Hygiène Sociale", 'Établissement public', 'Dakar', false],
            ['Hôpital Abass Ndao', 'Hôpital public', 'Dakar', false],
            ['Hôpital HOGIP', 'Hôpital public', 'Guédiawaye', false],
            ['Hôpital Albert Royer', 'Hôpital pédiatrique', 'Dakar', false],
            ['Hôpital des Enfants de Diamniadio', 'Hôpital pédiatrique', 'Diamniadio', true],
            ['Hôpital Dalal Jamm', 'Hôpital public', 'Guédiawaye', false],
            ['Hôpital Cheikhoul Khadim', 'Hôpital public', 'Touba', false],
            ['Hôpital Ndamatou', 'Hôpital public', 'Touba', false],
            ['Hôpital Mame Abdou Aziz', 'Hôpital public', 'Touba', false],
            ['Hôpital Saint Jean de Dieu', 'Hôpital confessionnel', 'Thiès', false],

            // --- Cliniques ---
            ['Polyclinique de Dakar', 'Clinique privée', 'Dakar', false],
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

        foreach ($etablissements as $position => [$nom, $type, $ville, $phare]) {
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
    }
}

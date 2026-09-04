<?php

namespace Database\Seeders;

use App\Models\Milestone;
use Illuminate\Database\Seeder;

class MilestoneSeeder extends Seeder
{
    public function run(): void
    {
        $milestones = [
            ['2015', 'Création d’AVENIR MEDICAL à Dakar', "L'aventure commence avec une petite équipe et une conviction : les structures de santé sénégalaises méritent du matériel neuf, fiable et bien suivi."],
            ['2018', 'Ouverture du service technique', "Recrutement de nos premiers ingénieurs et techniciens biomédicaux, pour assurer nous-mêmes la maintenance du matériel que nous vendons."],
            ['2021', 'Premiers partenariats internationaux', "Signature d'accords de distribution avec des fabricants reconnus, qui élargissent notre catalogue et sécurisent l'approvisionnement en pièces."],
            ['2023', 'Lancement d’Avenir Medical Consulting', "Un service dédié à l'accompagnement et au financement des projets d'ouverture de cliniques et de cabinets privés."],
            ['2026', 'Ouverture de la boutique en ligne', "Les particuliers et les professionnels peuvent désormais consulter nos prix et commander directement depuis notre site."],
        ];

        foreach ($milestones as $position => [$year, $title, $description]) {
            Milestone::query()->firstOrCreate(
                ['year' => $year, 'title' => $title],
                [
                    'description' => $description,
                    'position' => $position,
                    'is_active' => true,
                ]
            );
        }
    }
}

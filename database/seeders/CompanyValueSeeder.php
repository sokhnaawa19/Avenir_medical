<?php

namespace Database\Seeders;

use App\Models\CompanyValue;
use Illuminate\Database\Seeder;

class CompanyValueSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            [
                'title' => 'Qualité',
                'icon' => '💎',
                'description' => 'Du matériel neuf de qualité supérieure, conforme aux normes internationales, avec un service après-vente performant.',
            ],
            [
                'title' => 'Satisfaction client',
                'icon' => '🤝',
                'description' => 'Une réponse précise aux besoins de chaque client : hôpital, entreprise ou particulier.',
            ],
            [
                'title' => "Étendue de l'offre",
                'icon' => '🚀',
                'description' => 'Une gamme qui grandit sans cesse, pour devenir votre partenaire de choix en matériel médical.',
            ],
        ];

        foreach ($values as $position => $value) {
            CompanyValue::query()->firstOrCreate(
                ['title' => $value['title']],
                $value + ['position' => $position, 'is_active' => true]
            );
        }
    }
}

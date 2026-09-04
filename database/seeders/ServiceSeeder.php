<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Service après-vente',
                'icon' => '🛠️',
                'description' => 'Nous entretenons et réparons le matériel que nous vendons. Nos techniciens interviennent rapidement pour que votre matériel fonctionne toujours bien, année après année.',
            ],
            [
                'title' => 'Conseils & accompagnement',
                'icon' => '💡',
                'description' => 'Vous hésitez sur un équipement ? Notre équipe vous conseille et vous aide à bien utiliser votre matériel, pour en tirer le meilleur le plus longtemps possible.',
            ],
            [
                'title' => 'Avenir Medical Consulting',
                'icon' => '📊',
                'description' => 'Vous voulez ouvrir une clinique ou un cabinet ? Nous étudions votre projet, vous accompagnons pas à pas et vous proposons des solutions de financement adaptées à votre budget.',
            ],
        ];

        foreach ($services as $position => $service) {
            Service::query()->firstOrCreate(
                ['title' => $service['title']],
                $service + ['position' => $position, 'is_active' => true]
            );
        }
    }
}

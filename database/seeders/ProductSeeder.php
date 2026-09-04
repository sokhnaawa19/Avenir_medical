<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Tensiomètre électronique', 'Petits matériels', 45000, '🩺', true, 'Tensiomètre automatique au bras, écran large et lecture facile. Idéal pour le suivi de la tension à domicile ou en cabinet. Garantie 1 an.'],
            ['Stéthoscope double pavillon', 'Petits matériels', 25000, '🩺', false, "Stéthoscope professionnel double pavillon, excellente qualité d'écoute. Livré avec embouts de rechange."],
            ['Thermomètre infrarouge sans contact', 'Petits matériels', 18000, '🌡️', true, 'Prise de température en 1 seconde, sans contact. Mémoire des 30 dernières mesures. Piles incluses.'],
            ['Moniteur patient multiparamétrique', 'Petits matériels', 1450000, '📟', false, 'Moniteur de surveillance : tension, saturation, fréquence cardiaque, température. Écran couleur 12 pouces.'],
            ["Gants d'examen — boîte de 100", 'Consommables', 6500, '🧤', true, "Gants d'examen non poudrés, usage unique. Tailles S, M et L disponibles. Boîte distributrice de 100 gants."],
            ['Masques chirurgicaux — boîte de 50', 'Consommables', 4000, '😷', false, 'Masques chirurgicaux 3 plis, norme de qualité internationale. Boîte de 50 masques à usage unique.'],
            ['Kits de perfusion — lot de 50', 'Consommables', 12000, '💉', false, 'Kits de perfusion stériles à usage unique, lot de 50. Conditionnement individuel.'],
            ['Lit médicalisé 2 fonctions', 'Mobiliers hospitaliers', 850000, '🛏️', true, 'Lit médicalisé à 2 fonctions avec barrières rabattables et roues avec freins. Matelas non inclus. Livraison et installation possibles.'],
            ['Fauteuil roulant pliable', 'Mobiliers hospitaliers', 175000, '♿', false, 'Fauteuil roulant pliable en acier, repose-pieds amovibles. Léger et facile à transporter.'],
            ['Microscope de laboratoire', 'Laboratoire', 480000, '🔬', false, "Microscope binoculaire pour laboratoire d'analyses, éclairage LED, objectifs 4x / 10x / 40x / 100x."],
            ["Concentrateur d'oxygène 5 L", 'Oxygène & respiratoire', 650000, '💨', false, "Concentrateur d'oxygène 5 litres/minute, silencieux, avec alarmes de sécurité. Idéal pour les soins à domicile."],
            ['Réfrigérateur biomédical 120 L', 'Réfrigérateurs biomédicaux', 1250000, '❄️', false, 'Réfrigérateur biomédical 120 litres pour vaccins et produits sensibles. Température contrôlée avec alarme.'],
        ];

        foreach ($products as $position => [$name, $categoryName, $price, $emoji, $featured, $description]) {
            $category = Category::query()->where('name', $categoryName)->first();

            // Rattachement à un domaine et à une marque, à titre d'exemple.
            $domain = Domain::query()->inRandomOrder()->first();
            $brand = Partner::query()->inRandomOrder()->first();

            Product::query()->firstOrCreate(
                ['name' => $name],
                [
                    'category_id' => $category?->id,
                    'domain_id' => $domain?->id,
                    'partner_id' => $brand?->id,
                    'price' => $price,
                    'emoji' => $emoji,
                    'short_description' => str($description)->limit(120)->toString(),
                    'description' => $description,
                    'is_featured' => $featured,
                    'is_active' => true,
                    'position' => $position,
                ]
            );
        }
    }
}

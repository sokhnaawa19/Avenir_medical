<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('is_admin', true)->first();

        $posts = [
            [
                'title' => 'Nouveau partenariat signé avec un grand fabricant',
                'category' => 'Partenariat',
                'excerpt' => "Notre gamme d'imagerie médicale s'agrandit.",
                'days' => 18,
                'content' => "AVENIR MEDICAL est fier d'annoncer la signature d'un nouveau partenariat avec un grand fabricant international d'équipements d'imagerie médicale.\n\nCet accord nous permet d'élargir notre catalogue avec des équipements de dernière génération, tout en garantissant des pièces de rechange disponibles rapidement et un service après-vente encore plus réactif.\n\nCe que cela change pour vous : des délais de livraison plus courts, des prix plus compétitifs, et la garantie d'un matériel neuf, certifié et suivi dans la durée par nos techniciens formés directement par le fabricant.",
            ],
            [
                'title' => 'La boutique en ligne est ouverte !',
                'category' => 'Actualité',
                'excerpt' => 'Commandez votre matériel directement sur le site.',
                'days' => 32,
                'content' => "Vous pouvez désormais consulter nos prix et commander votre matériel médical directement depuis notre site internet.\n\nLes particuliers comme les professionnels y retrouvent nos petits matériels, nos consommables et notre mobilier hospitalier, avec livraison à Dakar et partout au Sénégal.",
            ],
            [
                'title' => 'Une clinique entièrement équipée à Dakar',
                'category' => 'Projet',
                'excerpt' => 'Retour en images sur ce beau projet.',
                'days' => 50,
                'content' => "Nos équipes ont accompagné l'ouverture d'une clinique privée à Dakar : étude du projet, choix des équipements, financement, installation et formation du personnel.\n\nUn projet mené de bout en bout par notre service technique et notre pôle consulting.",
            ],
            [
                'title' => 'Accord avec un laboratoire international',
                'category' => 'Partenariat',
                'excerpt' => 'De nouveaux consommables arrivent bientôt.',
                'days' => 69,
                'content' => "Un nouvel accord vient enrichir notre gamme de consommables biomédicaux, avec des références supplémentaires disponibles en stock à Dakar.",
            ],
            [
                'title' => 'Bien entretenir son matériel médical',
                'category' => 'Conseil',
                'excerpt' => 'Nos techniciens partagent leurs bons réflexes.',
                'days' => 83,
                'content' => "Nettoyage, vérification régulière, respect des conditions de stockage : quelques gestes simples permettent d'allonger nettement la durée de vie de vos équipements.\n\nNos techniciens biomédicaux vous accompagnent avec un contrat de maintenance préventive adapté à votre structure.",
            ],
            [
                'title' => 'AVENIR MEDICAL au salon de la santé',
                'category' => 'Actualité',
                'excerpt' => 'Venez nous rencontrer sur notre stand.',
                'days' => 101,
                'content' => "Nos équipes seront présentes au prochain salon de la santé pour présenter nos nouveaux équipements et échanger avec les professionnels du secteur.",
            ],
        ];

        foreach ($posts as $post) {
            Post::query()->firstOrCreate(
                ['title' => $post['title']],
                [
                    'user_id' => $author?->id,
                    'category' => $post['category'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'is_published' => true,
                    'published_at' => now()->subDays($post['days']),
                ]
            );
        }
    }
}

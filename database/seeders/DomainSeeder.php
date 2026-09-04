<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

/**
 * Contenu de départ des domaines d'intervention.
 *
 * Ce seeder ne remplace jamais un contenu déjà saisi dans l'administration :
 * il ne remplit que les champs restés vides. Il peut donc être relancé sans
 * risque après une mise à jour du site.
 *
 * Le texte des domaines est rangé dans database/seeders/data/domains.php
 * pour rester facile à relire et à modifier.
 */
class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = require database_path('seeders/data/domains.php');

        foreach ($domains as $position => $domain) {
            $model = Domain::query()->firstOrCreate(
                ['title' => $domain['title']],
                ['position' => $position, 'is_active' => true]
            );

            // On ne complète que ce qui est vide, pour ne rien écraser.
            foreach ($domain as $field => $value) {
                $current = $model->getAttribute($field);

                if ($current === null || $current === '' || $current === []) {
                    $model->setAttribute($field, $value);
                }
            }

            $model->save();
        }
    }
}

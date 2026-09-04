<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Rétablit les images des contenus : domaines, produits, marques,
 * établissements de référence, filiales, articles, étapes...
 *
 * Les autres seeders créent bien les lignes, mais sans image : ces photos
 * avaient été envoyées à la main depuis l'administration, et le lien entre
 * un contenu et son fichier n'existait que dans la base locale.
 *
 * Les fichiers, eux, sont déjà versionnés dans storage/app/public/.
 * La correspondance est notée dans database/seeders/data/site-media.json,
 * généré par le script dump-media.php.
 *
 * Le seeder ne remplace jamais une image choisie dans l'administration :
 * il ne remplit que les colonnes restées vides. Il peut donc être relancé
 * à chaque démarrage du conteneur.
 */
class SiteMediaSeeder extends Seeder
{
    /**
     * Les tables concernées, avec la colonne qui sert de repère
     * (la même que celle utilisée par les seeders existants)
     * et les colonnes contenant un fichier.
     *
     * @return array<string, array{cle: string, colonnes: array<int, string>}>
     */
    public static function carte(): array
    {
        return [
            'domains' => ['cle' => 'title', 'colonnes' => ['image']],
            'services' => ['cle' => 'title', 'colonnes' => ['image']],
            'posts' => ['cle' => 'title', 'colonnes' => ['image']],
            'process_steps' => ['cle' => 'title', 'colonnes' => ['image']],
            'milestones' => ['cle' => 'title', 'colonnes' => ['image']],
            'trainings' => ['cle' => 'title', 'colonnes' => ['image']],
            'products' => ['cle' => 'name', 'colonnes' => ['image']],
            'categories' => ['cle' => 'name', 'colonnes' => ['image']],
            'partners' => ['cle' => 'name', 'colonnes' => ['logo']],
            'establishments' => ['cle' => 'name', 'colonnes' => ['logo', 'image']],
            'subsidiaries' => ['cle' => 'name', 'colonnes' => ['logo', 'image']],
            'agencies' => ['cle' => 'name', 'colonnes' => ['image']],
        ];
    }

    public function run(): void
    {
        $fichier = database_path('seeders/data/site-media.json');

        if (! is_file($fichier)) {
            $this->command?->warn('site-media.json introuvable : seeder ignoré.');

            return;
        }

        /** @var array<string, array<string, array<string, string>>> $donnees */
        $donnees = json_decode((string) file_get_contents($fichier), true) ?: [];

        $carte = self::carte();
        $disque = Storage::disk('public');
        $total = 0;
        $absents = 0;

        foreach ($donnees as $table => $lignes) {
            if (! isset($carte[$table]) || ! Schema::hasTable($table)) {
                continue;
            }

            $cle = $carte[$table]['cle'];

            foreach ($lignes as $repere => $valeurs) {
                $aEcrire = [];

                foreach ($valeurs as $colonne => $chemin) {
                    if (blank($chemin)
                        || ! in_array($colonne, $carte[$table]['colonnes'], true)
                        || ! Schema::hasColumn($table, $colonne)) {
                        continue;
                    }

                    // Un lien vers un fichier absent afficherait une image cassée.
                    if (! $disque->exists($chemin)) {
                        $this->command?->warn('Fichier absent : '.$chemin.' ('.$table.')');
                        $absents++;

                        continue;
                    }

                    $aEcrire[$colonne] = $chemin;
                }

                if ($aEcrire === []) {
                    continue;
                }

                // On ne remplit que ce qui est vide, pour ne rien écraser.
                $requete = DB::table($table)->where($cle, $repere);

                foreach ($aEcrire as $colonne => $chemin) {
                    $modifiees = (clone $requete)
                        ->where(function ($q) use ($colonne) {
                            $q->whereNull($colonne)->orWhere($colonne, '');
                        })
                        ->update([$colonne => $chemin]);

                    $total += $modifiees;
                }
            }
        }

        if ($absents > 0) {
            $this->command?->warn($absents.' fichier(s) introuvable(s) sur le disque public.');
        }

        $this->command?->info($total.' image(s) de contenu rétablie(s).');
    }
}

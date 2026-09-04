<?php

namespace Database\Seeders;

use App\Services\SettingsRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Rétablit les médias du site (photo d'accueil, collage « à propos »,
 * aperçu de la vidéo) qui étaient envoyés à la main depuis l'administration.
 *
 * Les fichiers eux-mêmes sont déjà versionnés dans storage/app/public/site/.
 * Ce qui manquait sur un serveur fraîchement déployé, c'était uniquement le
 * lien « quel fichier va dans quel réglage », qui n'existait que dans la base
 * de données locale. La correspondance est désormais notée dans
 * database/seeders/data/site-images.json.
 *
 * Le seeder ne remplace jamais une image choisie dans l'administration :
 * il ne remplit que les réglages restés vides. Il peut donc être relancé
 * à chaque démarrage du conteneur sans rien écraser.
 */
class SiteImagesSeeder extends Seeder
{
    public function run(): void
    {
        $fichier = database_path('seeders/data/site-images.json');

        if (! is_file($fichier)) {
            $this->command?->warn('site-images.json introuvable : seeder ignoré.');

            return;
        }

        /** @var array<string, string|null> $correspondances */
        $correspondances = json_decode((string) file_get_contents($fichier), true) ?: [];

        $reglages = app(SettingsRepository::class);
        $disque = Storage::disk('public');
        $posees = 0;

        foreach ($correspondances as $cle => $chemin) {
            if (blank($chemin)) {
                continue;
            }

            // Le fichier doit être présent sur le disque public,
            // sinon on poserait un lien vers une image inexistante.
            if (! $disque->exists($chemin)) {
                $this->command?->warn('Fichier absent de storage/app/public : '.$chemin);

                continue;
            }

            // On ne touche pas à une image déjà choisie dans l'administration.
            if (filled($reglages->get($cle))) {
                continue;
            }

            $reglages->set($cle, $chemin);
            $posees++;
        }

        $reglages->flush();

        $this->command?->info($posees.' média(s) du site rétabli(s).');
    }
}

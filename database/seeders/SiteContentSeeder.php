<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\SettingsRepository;
use Illuminate\Database\Seeder;

/**
 * Enregistre les textes du site tels qu'ils ont été saisis dans
 * l'administration : coordonnées, accroches, titres de sections, chat...
 *
 * Ces valeurs n'existaient jusqu'ici que dans la base de données locale.
 * Sur un serveur fraîchement déployé, les réglages retombaient donc sur
 * les valeurs par défaut de config/settings.php, et le site s'affichait
 * avec des textes génériques.
 *
 * Règle de remplacement, pour ne jamais effacer une saisie volontaire :
 *
 *   - réglage absent ou vide                 -> on écrit la valeur
 *   - réglage encore sur sa valeur par défaut -> on écrit la valeur
 *   - réglage modifié dans l'administration   -> on n'y touche pas
 *
 * Le seeder peut donc être relancé à chaque démarrage du conteneur.
 */
class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $fichier = database_path('seeders/data/site-settings.json');

        if (! is_file($fichier)) {
            $this->command?->warn('site-settings.json introuvable : seeder ignoré.');

            return;
        }

        /** @var array<string, string|null> $contenus */
        $contenus = json_decode((string) file_get_contents($fichier), true) ?: [];

        $defauts = $this->defautsDuSchema();
        $existants = Setting::query()->pluck('value', 'key');
        $ecrits = 0;

        foreach ($contenus as $cle => $valeur) {
            if (blank($valeur)) {
                continue;
            }

            $actuelle = $existants->get($cle);

            // Une valeur saisie dans l'administration est respectée.
            if (filled($actuelle)
                && $actuelle !== ($defauts[$cle] ?? null)
                && $actuelle !== $valeur) {
                continue;
            }

            // Rien à faire si la valeur est déjà celle attendue.
            if ($actuelle === $valeur) {
                continue;
            }

            Setting::query()->updateOrCreate(
                ['key' => $cle],
                [
                    'value' => $valeur,
                    'group' => $this->groupeDe($cle) ?? 'general',
                    'type' => $this->typeDe($cle) ?? 'text',
                ]
            );

            $ecrits++;
        }

        // Les réglages sont mis en cache « pour toujours » :
        // sans ce vidage, le site continuerait d'afficher les anciens textes.
        app(SettingsRepository::class)->flush();

        $this->command?->info($ecrits.' texte(s) du site enregistré(s).');
    }

    /**
     * Les valeurs par défaut déclarées dans config/settings.php.
     *
     * @return array<string, string|null>
     */
    private function defautsDuSchema(): array
    {
        $defauts = [];

        foreach (config('settings', []) as $definition) {
            foreach ($definition['fields'] ?? [] as $cle => $champ) {
                $defauts[$cle] = $champ['default'] ?? null;
            }
        }

        return $defauts;
    }

    private function groupeDe(string $cle): ?string
    {
        foreach (config('settings', []) as $groupe => $definition) {
            if (array_key_exists($cle, $definition['fields'] ?? [])) {
                return $groupe;
            }
        }

        return null;
    }

    private function typeDe(string $cle): ?string
    {
        foreach (config('settings', []) as $definition) {
            if (isset($definition['fields'][$cle])) {
                return $definition['fields'][$cle]['type'] ?? 'text';
            }
        }

        return null;
    }
}

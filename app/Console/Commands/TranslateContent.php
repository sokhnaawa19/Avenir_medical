<?php

namespace App\Console\Commands;

use App\Models\Agency;
use App\Models\Category;
use App\Models\CompanyValue;
use App\Models\Domain;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Milestone;
use App\Models\Partner;
use App\Models\Post;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Subsidiary;
use App\Models\Training;
use App\Services\Translation\Translator;
use Illuminate\Console\Command;
use Throwable;

/**
 * Traduit tout le contenu du site vers l'anglais.
 *
 *   php artisan site:traduire              traduit ce qui ne l'est pas encore
 *   php artisan site:traduire --tout       retraduit aussi les textes déjà traduits
 *   php artisan site:traduire --essai      montre ce qui serait traduit, sans rien envoyer
 */
class TranslateContent extends Command
{
    /** Réglages qui ne doivent jamais être traduits. */
    private const CLES_JAMAIS_TRADUITES = [
        // Nom de l'entreprise, adresse et numéros : jamais traduits.
        // Les intitulés de service (« Laboratoires & équipements ») le sont,
        // eux, puisqu'ils décrivent un service et non un nom propre.
        'site_name', 'site_baseline', 'address', 'phone_1', 'phone_2', 'phone_3',
    ];

    protected $signature = 'site:traduire
        {--locale=en : la langue de destination}
        {--tout : retraduire aussi les textes déjà traduits}
        {--essai : simuler, sans appeler le service de traduction}
        {--interface : traduire aussi les textes du site (menus, boutons, messages)}';

    protected $description = 'Traduit le contenu du site (produits, domaines, articles…)';

    /** Les contenus à traduire. */
    private array $modeles = [
        Domain::class, Category::class, Product::class, Service::class,
        ProcessStep::class, Post::class, CompanyValue::class, Partner::class,
        Milestone::class, Establishment::class, Training::class,
        Agency::class, Subsidiary::class, GalleryPhoto::class,
    ];

    public function handle(): int
    {
        $locale = (string) $this->option('locale');
        $essai = (bool) $this->option('essai');
        $traducteur = Translator::make();

        if (! $essai && ! $traducteur->configured()) {
            $this->error("Aucune clé de traduction n'est configurée.");
            $this->line('  Ajoutez dans votre fichier .env :');
            $this->line('     TRANSLATE_DRIVER=deepl');
            $this->line('     TRANSLATE_KEY=votre-cle');
            $this->newLine();
            $this->line('  Puis relancez : <fg=cyan>php artisan site:traduire</>');

            return self::FAILURE;
        }

        $traduits = 0;
        $erreurs = 0;
        $aTraduire = 0;

        foreach ($this->modeles as $classe) {
            $nom = class_basename($classe);
            $elements = $classe::query()->with('translations')->get();

            if ($elements->isEmpty()) {
                continue;
            }

            $this->line("  <options=bold>{$nom}</> ({$elements->count()})");

            foreach ($elements as $element) {
                foreach ($element->translatableFields() as $champ) {
                    $original = $element->raw($champ);

                    if (blank($original)) {
                        continue;
                    }

                    // Champ en liste (équipements) : on traduit chaque texte
                    // en conservant la structure.
                    if (is_array($original) || $this->ressembleAUneListe($original)) {
                        $liste = is_array($original) ? $original : json_decode($original, true);

                        if (! is_array($liste)) {
                            continue;
                        }

                        $existante = $element->translationFor($champ, $locale);

                        if (filled($existante) && ! $this->option('tout')) {
                            continue;
                        }

                        $aTraduire++;

                        if ($essai) {
                            continue;
                        }

                        try {
                            $element->setTranslation(
                                $champ,
                                $locale,
                                json_encode($this->traduireListe($liste, $traducteur, $locale), JSON_UNESCAPED_UNICODE)
                            );
                            $traduits++;
                        } catch (Throwable $e) {
                            $erreurs++;
                            $this->warn('    '.$champ.' : '.$e->getMessage());
                        }

                        continue;
                    }

                    if (! is_string($original)) {
                        continue;
                    }

                    $existante = $element->translationFor($champ, $locale);

                    if (filled($existante) && ! $this->option('tout')) {
                        continue;
                    }

                    $aTraduire++;

                    if ($essai) {
                        continue;
                    }

                    try {
                        $element->setTranslation($champ, $locale, $traducteur->translate($original, $locale));
                        $traduits++;
                    } catch (Throwable $e) {
                        $erreurs++;
                        $this->warn("    {$champ} : ".$e->getMessage());
                    }
                }
            }
        }

        // Les textes de l'interface (fichiers lang/)
        if ($this->option('interface')) {
            $aTraduire += $this->traduireInterface($traducteur, $locale, $essai, $traduits, $erreurs);
        }

        // Les réglages du site (titres, textes des sections…)
        $aTraduire += $this->traduireReglages($traducteur, $locale, $essai, $traduits, $erreurs);

        $this->newLine();

        if ($essai) {
            $this->info("  {$aTraduire} texte(s) seraient traduits.");
            $this->line('  <fg=gray>Relancez sans --essai pour lancer la traduction.</>');

            return self::SUCCESS;
        }

        $this->info("  {$traduits} texte(s) traduit(s).");

        if ($erreurs > 0) {
            $this->warn("  {$erreurs} échec(s) — relancez la commande pour réessayer.");
        }

        $this->line('  <fg=gray>Relisez et corrigez les textes importants dans Administration → Traductions.</>');

        return self::SUCCESS;
    }

    /**
     * Traduit les textes du site eux-mêmes (lang/fr/site.php vers lang/en/site.php).
     *
     * Ces textes ne sont pas en base : ce sont les menus, les boutons et les
     * messages. Seules les valeurs encore identiques au français sont envoyées.
     */
    private function traduireInterface(Translator $traducteur, string $locale, bool $essai, int &$traduits, int &$erreurs): int
    {
        $defaut = config('app.fallback_locale', 'fr');
        $sourceChemin = lang_path($defaut.'/site.php');
        $cibleChemin = lang_path($locale.'/site.php');

        if (! file_exists($sourceChemin) || ! file_exists($cibleChemin)) {
            return 0;
        }

        $source = require $sourceChemin;
        $cible = require $cibleChemin;

        $this->line('  <options=bold>Textes du site</> (lang/'.$locale.'/site.php)');

        $compte = 0;

        $parcourir = function (array $fr, array $en) use (&$parcourir, $traducteur, $locale, $essai, &$traduits, &$erreurs, &$compte): array {
            $resultat = $en;

            foreach ($fr as $cle => $valeur) {
                if (is_array($valeur)) {
                    $resultat[$cle] = $parcourir($valeur, $en[$cle] ?? []);

                    continue;
                }

                $actuel = $en[$cle] ?? null;

                // Déjà traduit à la main : on n'y touche pas.
                if (is_string($actuel) && $actuel !== $valeur && filled($actuel)) {
                    continue;
                }

                if (blank($valeur)) {
                    continue;
                }

                $compte++;

                if ($essai) {
                    continue;
                }

                try {
                    $resultat[$cle] = $traducteur->translate($valeur, $locale);
                    $traduits++;
                } catch (Throwable $e) {
                    $erreurs++;
                    $this->warn('    '.$cle.' : '.$e->getMessage());
                }
            }

            return $resultat;
        };

        $traduit = $parcourir($source, $cible);

        if (! $essai) {
            file_put_contents(
                $cibleChemin,
                "<?php\n\n/**\n * Textes de l'interface.\n * Complété par « php artisan site:traduire --interface ».\n * Une valeur corrigée à la main n'est jamais écrasée.\n */\nreturn ".$this->exporter($traduit).";\n"
            );
        }

        return $compte;
    }

    /** Écrit un tableau PHP lisible. */
    private function exporter(array $valeurs, int $niveau = 1): string
    {
        $marge = str_repeat('    ', $niveau);
        $lignes = [];

        foreach ($valeurs as $cle => $valeur) {
            $lignes[] = is_array($valeur)
                ? $marge."'".$cle."' => ".$this->exporter($valeur, $niveau + 1).','
                : $marge."'".$cle."' => '".str_replace(["\\\\", "'"], ["\\\\\\\\", "\\'"], $valeur)."',";
        }

        return "[\n".implode("\n", $lignes)."\n".str_repeat('    ', $niveau - 1).']';
    }

    /** Les réglages textuels : titres et textes des sections. */
    /**
     * Crée en base les réglages qui n'y sont pas encore.
     *
     * Tant qu'un réglage n'a jamais été enregistré depuis l'administration,
     * il n'existe pas en base : sa valeur vient de config/settings.php.
     * Il était donc invisible pour la traduction. On l'enregistre d'abord
     * avec sa valeur par défaut, sans rien modifier de ce qui existe.
     */
    private function creerReglagesManquants(array $exclus): int
    {
        $existants = Setting::query()->pluck('key')->all();
        $traduisibles = ['text', 'textarea'];
        $crees = 0;

        foreach (config('settings', []) as $groupe => $definition) {
            if (in_array($groupe, $exclus, true)) {
                continue;
            }

            foreach ($definition['fields'] ?? [] as $cle => $champ) {
                if (in_array($cle, $existants, true) || in_array($cle, self::CLES_JAMAIS_TRADUITES, true)) {
                    continue;
                }

                $type = $champ['type'] ?? 'text';
                $defaut = $champ['default'] ?? null;

                // On ne crée que les textes réellement remplis.
                if (! in_array($type, $traduisibles, true) || blank($defaut)) {
                    continue;
                }

                Setting::query()->create([
                    'key' => $cle,
                    'value' => $defaut,
                    'group' => $groupe,
                    'type' => $type,
                ]);

                $crees++;
            }
        }

        if ($crees > 0) {
            settings()->flush();
        }

        return $crees;
    }

    /** Le texte enregistré est-il en réalité une liste JSON ? */
    private function ressembleAUneListe(mixed $valeur): bool
    {
        return is_string($valeur)
            && str_starts_with(trim($valeur), '[')
            && is_array(json_decode($valeur, true));
    }

    /**
     * Traduit chaque texte d'une liste, quelle que soit sa profondeur.
     */
    private function traduireListe(array $liste, Translator $traducteur, string $locale): array
    {
        foreach ($liste as $cle => $valeur) {
            if (is_array($valeur)) {
                $liste[$cle] = $this->traduireListe($valeur, $traducteur, $locale);

                continue;
            }

            if (is_string($valeur) && filled($valeur)) {
                $liste[$cle] = $traducteur->translate($valeur, $locale);
            }
        }

        return $liste;
    }

    /** Combien de réglages manquent encore en base ? */
    private function compterReglagesManquants(array $exclus): int
    {
        $existants = Setting::query()->pluck('key')->all();
        $absents = 0;

        foreach (config('settings', []) as $groupe => $definition) {
            if (in_array($groupe, $exclus, true)) {
                continue;
            }

            foreach ($definition['fields'] ?? [] as $cle => $champ) {
                if (in_array($cle, $existants, true) || in_array($cle, self::CLES_JAMAIS_TRADUITES, true)) {
                    continue;
                }

                if (in_array($champ['type'] ?? 'text', ['text', 'textarea'], true) && filled($champ['default'] ?? null)) {
                    $absents++;
                }
            }
        }

        return $absents;
    }

    private function traduireReglages(Translator $traducteur, string $locale, bool $essai, int &$traduits, int &$erreurs): int
    {
        // Ne restent en français que le nom, les coordonnées et les liens :
        // les titres de vidéos et les textes de référencement se traduisent.
        // Le nom et les liens ne se traduisent pas. Les coordonnées non plus,
        // mais elles sont déjà protégées par leur clé (adresse, téléphones) ou
        // par leur type (email, url) : les horaires, eux, doivent être traduits.
        // Seuls les liens restent hors traduction ; le nom de l'entreprise
        // est protégé individuellement par CLES_JAMAIS_TRADUITES.
        $exclus = ['reseaux'];
        $compte = 0;

        if ($essai) {
            // En simulation, on annonce ce qui serait enregistré puis traduit.
            $absents = $this->compterReglagesManquants($exclus);

            if ($absents > 0) {
                $this->line('  <fg=gray>'.$absents.' réglage(s) absents de la base seraient enregistrés puis traduits.</>');
            }

            $compte += $absents;
        } else {
            // Les réglages jamais enregistrés doivent d'abord exister en base.
            $crees = $this->creerReglagesManquants($exclus);

            if ($crees > 0) {
                $this->line('  <fg=gray>'.$crees.' réglage(s) enregistré(s) depuis leurs valeurs par défaut.</>');
            }
        }

        foreach (Setting::query()->with('translations')->get() as $reglage) {
            if (in_array($reglage->key, self::CLES_JAMAIS_TRADUITES, true)
                || in_array($reglage->group, $exclus, true) || in_array($reglage->type, ['image', 'video', 'boolean', 'number', 'tel', 'email', 'url'], true)) {
                continue;
            }

            if (blank($reglage->raw('value'))) {
                continue;
            }

            if (filled($reglage->translationFor('value', $locale)) && ! $this->option('tout')) {
                continue;
            }

            $compte++;

            if ($essai) {
                continue;
            }

            try {
                $reglage->setTranslation('value', $locale, $traducteur->translate((string) $reglage->raw('value'), $locale));
                $traduits++;
            } catch (Throwable $e) {
                $erreurs++;
                $this->warn("    {$reglage->key} : ".$e->getMessage());
            }
        }

        return $compte;
    }
}

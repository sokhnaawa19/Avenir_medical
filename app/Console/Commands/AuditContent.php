<?php

namespace App\Console\Commands;

use App\Models\Agency;
use App\Models\Domain;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Milestone;
use App\Models\Partner;
use App\Models\Post;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subsidiary;
use App\Models\Training;
use Illuminate\Console\Command;

/**
 * Fait le point sur ce qui est rempli et ce qui manque encore.
 *
 *   php artisan site:manquant
 *
 * Utile pour savoir exactement quoi demander au client,
 * sans avoir à parcourir toute l'administration.
 */
class AuditContent extends Command
{
    protected $signature = 'site:manquant {--tout : afficher aussi ce qui est déjà rempli}';

    protected $description = 'Liste les contenus encore manquants sur le site';

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>Ce qui manque encore sur le site</>');
        $this->newLine();

        $this->rubriques();
        $this->reglages();
        $this->images();

        $this->newLine();
        $this->line('  <fg=gray>Astuce : « php artisan site:manquant --tout » affiche aussi ce qui est déjà rempli.</>');
        $this->newLine();

        return self::SUCCESS;
    }

    /** Les rubriques de contenu (produits, références, formations…). */
    private function rubriques(): void
    {
        $rubriques = [
            ['Domaines', Domain::query()->count(), 12],
            ['Produits', Product::query()->count(), 20],
            ['Services', Service::query()->count(), 3],
            ['Parcours client', ProcessStep::query()->count(), 5],
            ['Partenaires', Partner::query()->count(), 5],
            ['Partenariats exclusifs', Partner::query()->where('is_exclusive', true)->count(), 1],
            ['Références (établissements)', Establishment::query()->count(), 6],
            ['Formations', Training::query()->count(), 3],
            ['Photos de la galerie', GalleryPhoto::query()->count(), 20],
            ["Étapes de l'historique", Milestone::query()->count(), 4],
            ['Entreprises du groupe', Subsidiary::query()->count(), 2],
            ['Agences', Agency::query()->count(), 2],
            ['Articles du blog', Post::query()->count(), 3],
        ];

        $this->line('  <options=bold>Rubriques de contenu</>');

        foreach ($rubriques as [$nom, $actuel, $conseille]) {
            $manque = $actuel < $conseille;

            if (! $manque && ! $this->option('tout')) {
                continue;
            }

            $icone = $actuel === 0 ? '<fg=red>✗</>' : ($manque ? '<fg=yellow>~</>' : '<fg=green>✓</>');
            $etat = $actuel === 0
                ? 'aucun contenu'
                : $actuel.' enregistré(s)'.($manque ? ' — '.$conseille.' conseillés' : '');

            $this->line(sprintf('  %s  %-30s %s', $icone, $nom, $etat));
        }

        $this->newLine();
    }

    /** Les réglages textuels laissés vides. */
    private function reglages(): void
    {
        $this->line('  <options=bold>Réglages non renseignés</>');
        $vides = 0;

        foreach (config('settings', []) as $groupe => $definition) {
            $manquants = [];

            foreach ($definition['fields'] ?? [] as $cle => $champ) {
                if (($champ['type'] ?? 'text') === 'boolean') {
                    continue;
                }

                if (blank(setting($cle))) {
                    $manquants[] = $champ['label'] ?? $cle;
                }
            }

            if ($manquants === []) {
                continue;
            }

            $vides += count($manquants);
            $this->line(sprintf('  <fg=yellow>~</>  %s', $definition['label'] ?? $groupe));

            foreach ($manquants as $libelle) {
                $this->line('       <fg=gray>· '.$libelle.'</>');
            }
        }

        if ($vides === 0) {
            $this->line('  <fg=green>✓</>  Tous les réglages sont renseignés.');
        }

        $this->newLine();
    }

    /** Les contenus enregistrés mais sans image. */
    private function images(): void
    {
        $sansImage = [
            ['Domaines sans photo', Domain::query()->whereNull('image')->count()],
            ['Produits sans photo', Product::query()->whereNull('image')->count()],
            ['Partenaires sans logo', Partner::query()->whereNull('logo')->count()],
            ['Services sans photo', Service::query()->whereNull('image')->count()],
            ['Articles sans photo', Post::query()->whereNull('image')->count()],
            ['Formations sans photo', Training::query()->whereNull('image')->count()],
            ['Références sans photo', Establishment::query()->whereNull('image')->count()],
        ];

        $this->line('  <options=bold>Contenus sans image</>');
        $total = 0;

        foreach ($sansImage as [$nom, $nombre]) {
            if ($nombre === 0) {
                continue;
            }

            $total += $nombre;
            $this->line(sprintf('  <fg=yellow>~</>  %-30s %d', $nom, $nombre));
        }

        if ($total === 0) {
            $this->line('  <fg=green>✓</>  Tous les contenus ont une image.');
        }
    }
}

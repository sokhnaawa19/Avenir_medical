<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Vide la boutique de ses produits.
 *
 *   php artisan boutique:vider                 (demande confirmation)
 *   php artisan boutique:vider --force         (sans confirmation)
 *   php artisan boutique:vider --avec-images   (supprime aussi les photos)
 *
 * Les commandes déjà passées ne sont pas touchées : chaque ligne de commande
 * conserve le nom et le prix de l'article au moment de l'achat.
 */
class ClearShop extends Command
{
    protected $signature = 'boutique:vider {--force : Ne pas demander de confirmation}
                                           {--avec-images : Supprimer aussi les fichiers photo}';

    protected $description = 'Supprime tous les produits de la boutique';

    public function handle(): int
    {
        $count = Product::query()->count();

        if ($count === 0) {
            $this->info('La boutique est déjà vide.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm($count.' produits vont être supprimés. Continuer ?')) {
            $this->warn('Annulé, rien n\'a été supprimé.');

            return self::SUCCESS;
        }

        if ($this->option('avec-images')) {
            $this->deleteImages();
        }

        // Les lignes de commande gardent le nom et le prix : leur lien
        // vers le produit passe simplement à vide.
        Product::query()->delete();

        $this->info($count.' produits supprimés.');

        return self::SUCCESS;
    }

    /** Supprime les fichiers photo des produits sur le disque public. */
    private function deleteImages(): void
    {
        $disk = Storage::disk('public');
        $deleted = 0;

        Product::query()
            ->whereNotNull('image')
            ->pluck('image')
            ->each(function (string $path) use ($disk, &$deleted): void {
                if ($disk->exists($path)) {
                    $disk->delete($path);
                    $deleted++;
                }
            });

        $this->line($deleted.' fichiers photo supprimés.');
    }
}

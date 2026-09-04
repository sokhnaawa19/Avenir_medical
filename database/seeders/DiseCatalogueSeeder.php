<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Remplit la boutique avec le catalogue revendeur DISE 2026.
 *
 * Le seeder crée les rayons manquants, puis chaque article avec son prix, son
 * conditionnement, sa photo et sa fiche. Les articles sont retrouvés par leur
 * référence : relancer le seeder met à jour les prix sans créer de doublon.
 *
 * Les anciens produits ne sont pas supprimés ici. Pour vider la boutique
 * avant d'importer, utilisez : php artisan boutique:vider
 *
 * Le contenu est rangé dans database/seeders/data/dise-catalogue.php.
 */
class DiseCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array<string, mixed>> $articles */
        $articles = require database_path('seeders/data/dise-catalogue.php');

        // Tous ces articles sont des consommables de la marque DISE.
        $domain = Domain::query()->where('title', 'Consommables biomédicaux')->first();
        $brand = Partner::query()->where('name', 'DISE')->first();

        if ($brand === null) {
            $this->command?->warn('Marque DISE introuvable : lancez d\'abord PartnerSeeder.');
        }

        $categories = $this->categories($articles);

        foreach ($articles as $position => $article) {
            $product = Product::query()->firstOrNew(['reference' => $article['reference']]);

            $product->fill([
                'category_id' => $categories[$article['category']] ?? null,
                'domain_id' => $domain?->id,
                'partner_id' => $brand?->id,
                'name' => $article['name'],
                'short_description' => $article['packaging'].' · '.$article['specs'],
                'description' => $article['description'],
                'price' => (int) $article['price'],
                'emoji' => $article['emoji'],
                'image' => filled($article['image'] ?? null) ? 'produits/'.$article['image'] : null,
                'is_featured' => (bool) $article['featured'],
                'is_active' => true,
                'position' => $position,
            ]);

            if (blank($product->slug)) {
                $product->slug = Str::slug($article['name']);
            }

            $product->save();
        }

        $this->tidyCategories();

        $this->command?->info(count($articles).' articles DISE enregistrés.');
    }

    /**
     * Crée les rayons du catalogue et renvoie leurs identifiants par nom.
     *
     * @param  array<int, array<string, mixed>>  $articles
     * @return array<string, int>
     */
    private function categories(array $articles): array
    {
        $names = array_values(array_unique(array_column($articles, 'category')));
        $ids = [];

        foreach ($names as $position => $name) {
            $category = Category::query()->firstOrCreate(
                ['name' => $name],
                ['position' => $position, 'is_active' => true]
            );

            // Un rayon désactivé auparavant redevient visible s'il est réutilisé.
            if (! $category->is_active) {
                $category->is_active = true;
                $category->save();
            }

            $ids[$name] = $category->id;
        }

        return $ids;
    }

    /**
     * Masque les rayons restés sans article, pour ne pas afficher « (0) »
     * dans les filtres de la boutique. Rien n'est supprimé : il suffit de les
     * réactiver depuis Administration → Catégories.
     */
    private function tidyCategories(): void
    {
        Category::query()
            ->where('is_active', true)
            ->whereDoesntHave('products', fn ($query) => $query->where('is_active', true))
            ->update(['is_active' => false]);
    }
}

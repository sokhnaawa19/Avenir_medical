<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNavigationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        return User::factory()->admin()->create();
    }

    private function produits(int $nombre = 45): void
    {
        $category = Category::query()->create(['name' => 'Consommables']);

        foreach (range(1, $nombre) as $i) {
            Product::query()->create([
                'name' => 'Produit '.$i,
                'price' => 1000 * $i,
                'category_id' => $category->id,
                'is_active' => true,
                'position' => $i,
            ]);
        }
    }

    public function test_apres_suppression_on_revient_sur_la_meme_page(): void
    {
        $admin = $this->admin();
        $this->produits();

        // L'administrateur consulte la page 3
        $this->actingAs($admin)->get('/admin/produits?page=3')->assertOk();

        $produit = Product::query()->where('name', 'Produit 42')->first();

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $produit))
            ->assertRedirect('/admin/produits?page=3');
    }

    public function test_les_filtres_sont_conserves_apres_modification(): void
    {
        $admin = $this->admin();
        $this->produits();

        $this->actingAs($admin)->get('/admin/produits?page=2&q=Produit&state=actifs')->assertOk();

        $produit = Product::query()->first();

        $reponse = $this->actingAs($admin)->put(route('admin.products.update', $produit), [
            'name' => 'Produit modifié',
            'price' => 5000,
            'is_active' => 1,
        ]);

        $reponse->assertRedirect();
        $url = $reponse->headers->get('Location');

        $this->assertStringContainsString('page=2', $url);
        $this->assertStringContainsString('q=Produit', $url);
    }

    public function test_les_boutons_de_suppression_demandent_confirmation(): void
    {
        $admin = $this->admin();
        $this->produits(3);

        $this->actingAs($admin)
            ->get('/admin/produits')
            ->assertOk()
            // La fenêtre de confirmation s'appuie sur ces deux attributs.
            ->assertSee('data-confirm=', false)
            ->assertSee('data-confirm-name=', false);
    }

    public function test_une_page_vide_propose_de_revenir_au_debut(): void
    {
        $admin = $this->admin();
        $this->produits(3);

        $this->actingAs($admin)
            ->get('/admin/produits?page=9')
            ->assertOk()
            ->assertSee('Revenir à la première page');
    }
}

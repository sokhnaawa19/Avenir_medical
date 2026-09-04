<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_accueil_montre_seulement_trois_domaines(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        for ($i = 1; $i <= 6; $i++) {
            Domain::query()->create([
                'title' => 'Domaine '.$i,
                'position' => $i,
                'is_active' => true,
            ]);
        }

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(3, substr_count($html, 'class="dom"'));
    }

    public function test_l_accueil_n_a_plus_les_blocs_d_acces_rapide(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->get('/')->assertOk()->assertDontSee('class="tease"', false);
    }

    public function test_les_prix_en_promotion_sont_visibles_dans_la_boutique(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $category = Category::query()->create(['name' => 'Test']);

        Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Tensiomètre',
            'price' => 45000,
            'old_price' => 60000,
            'is_active' => true,
            'is_featured' => true,
        ]);

        foreach (['/', '/boutique'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('60 000', false)   // ancien prix barré
                ->assertSee('45 000', false)   // nouveau prix
                ->assertSee('promo-badge', false);
        }
    }

    public function test_les_logos_partenaires_restent_en_couleur(): void
    {
        $css = file_get_contents(public_path('assets/css/style.css'));

        $this->assertStringNotContainsString('grayscale', $css);
    }

    public function test_les_couleurs_et_logos_ne_sont_plus_reglables(): void
    {
        $champs = config('settings.identite.fields');

        foreach (['logo', 'logo_light', 'favicon', 'color_primary', 'color_dark', 'color_light'] as $cle) {
            $this->assertArrayNotHasKey($cle, $champs);
        }

        $this->assertArrayHasKey('site_name', $champs);
    }
}

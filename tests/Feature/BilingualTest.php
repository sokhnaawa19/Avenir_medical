<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BilingualTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_deux_versions_du_site_repondent(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        foreach (['/', '/boutique', '/services', '/contact'] as $page) {
            $this->get($page)->assertOk();
            $this->get('/en'.rtrim($page, '/'))->assertOk();
        }
    }

    public function test_le_contenu_traduit_s_affiche_en_anglais(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $domaine = Domain::query()->create([
            'title' => 'Imagerie médicale',
            'subtitle' => 'Radiologie et échographie',
            'is_active' => true,
        ]);

        $domaine->setTranslation('title', 'en', 'Medical imaging');

        $this->get('/')->assertOk()->assertSee('Imagerie médicale');
        $this->get('/en')->assertOk()->assertSee('Medical imaging')->assertDontSee('Imagerie médicale');
    }

    public function test_sans_traduction_le_francais_est_affiche(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Domain::query()->create(['title' => 'Centre de dialyse', 'is_active' => true]);

        // Aucune traduction enregistrée : la page anglaise ne doit pas être vide.
        $this->get('/en')->assertOk()->assertSee('Centre de dialyse');
    }

    public function test_l_interface_est_traduite(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->get('/')->assertOk()->assertSee('Accueil');
        $this->get('/en')->assertOk()->assertSee('Home')->assertSee('Our fields');
    }

    public function test_les_balises_hreflang_sont_presentes(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->get('/boutique')
            ->assertOk()
            ->assertSee('hreflang="fr"', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('/en/boutique', false);
    }

    public function test_un_reglage_traduit_s_affiche(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        // Le titre n'apparaît que si la section a du contenu.
        Domain::query()->create(['title' => 'Imagerie médicale', 'is_active' => true]);

        Setting::query()->where('key', 'domains_title')->first()
            ?->setTranslation('value', 'en', 'A solution for every care environment');

        settings()->flush();

        $this->get('/en')->assertOk()->assertSee('A solution for every care environment');
    }

    public function test_la_version_anglaise_ne_multiplie_pas_les_requetes(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        foreach (range(1, 12) as $i) {
            Product::query()->create([
                'name' => 'Produit '.$i,
                'price' => 1000 * $i,
                'is_active' => true,
                'position' => $i,
            ]);
        }

        DB::enableQueryLog();
        $this->get('/en/boutique')->assertOk();
        $requetes = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Sans chargement anticipé, 12 produits ajouteraient 12 requêtes.
        $this->assertLessThan(20, $requetes, 'Les traductions ne sont pas chargées à l’avance.');
    }
}

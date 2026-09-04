<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Partner;
use App\Models\Subsidiary;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupAndReferencesTest extends TestCase
{
    use RefreshDatabase;

    private function contenus(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Partner::query()->create([
            'name' => 'Mindray',
            'is_active' => true,
            'is_exclusive' => true,
            'exclusivity_scope' => "Afrique de l'Ouest",
        ]);

        Establishment::query()->create([
            'name' => 'Clinique de la Madeleine',
            'type' => 'Clinique privée',
            'city' => 'Dakar',
            'is_flagship' => true,
            'is_featured' => true,
            'is_active' => true,
            'equipments' => "Bloc opératoire\nImagerie",
        ]);

        Training::query()->create([
            'title' => 'Maintenance des échographes',
            'organism' => 'Mindray Academy',
            'country' => 'Chine',
            'participants' => 3,
            'is_active' => true,
        ]);

        Agency::query()->create([
            'name' => 'Agence de Thiès',
            'region' => 'Thiès',
            'country' => 'Sénégal',
            'status' => Agency::STATUT_PROJET,
            'is_active' => true,
        ]);

        Subsidiary::query()->create([
            'name' => 'AVENIR PHARMA',
            'activity' => 'Distribution',
            'is_active' => true,
        ]);

        GalleryPhoto::query()->create([
            'title' => 'Salon de la santé',
            'album' => 'Événements',
            'image' => 'galerie/photo.webp',
            'is_active' => true,
        ]);
    }

    public function test_la_page_references_montre_les_etablissements_equipes(): void
    {
        $this->contenus();

        $this->get(route('references'))
            ->assertOk()
            ->assertSee('Clinique de la Madeleine')
            ->assertSee('Bloc opératoire')
            ->assertSee('Équipement complet');
    }

    public function test_la_page_services_montre_exclusivites_et_formations(): void
    {
        $this->contenus();

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('Mindray')
            ->assertSee("Exclusivité Afrique de l'Ouest")
            ->assertSee('Maintenance des échographes')
            ->assertSee('techniciens formés');
    }

    public function test_la_page_entreprise_montre_le_groupe_et_les_agences(): void
    {
        $this->contenus();

        $this->get(route('company'))
            ->assertOk()
            ->assertSee('Maison mère')
            ->assertSee('AVENIR PHARMA')
            // Un projet apparaît dans le bloc « Notre ambition », pas dans les agences ouvertes.
            ->assertSee('Agence de Thiès')
            ->assertSee('Notre ambition');
    }

    public function test_la_galerie_affiche_les_photos_et_ses_albums(): void
    {
        $this->contenus();

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Salon de la santé')
            ->assertSee('Événements');
    }

    public function test_l_accueil_met_en_avant_exclusivites_references_et_groupe(): void
    {
        $this->contenus();

        $this->get('/')
            ->assertOk()
            ->assertSee('Mindray')
            ->assertSee('Clinique de la Madeleine')
            ->assertSee('AVENIR PHARMA');
    }

    public function test_les_projets_ne_sont_jamais_presentes_comme_realises(): void
    {
        $this->contenus();

        $page = $this->get(route('company'))->assertOk();

        // L'agence de Thiès est un projet : elle doit être annoncée comme telle.
        $page->assertSee('Notre ambition')
            ->assertSee('projets de développement');
    }

    public function test_les_anciennes_adresses_redirigent(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->get('/partenaires')->assertRedirect('/services');
        $this->get('/expertise')->assertRedirect('/services');
        $this->get('/le-groupe')->assertRedirect('/entreprise');
    }

    public function test_les_contenus_masques_n_apparaissent_pas(): void
    {
        $this->contenus();

        Establishment::query()->update(['is_active' => false]);
        Subsidiary::query()->update(['is_active' => false]);

        $this->get(route('references'))->assertOk()->assertDontSee('Clinique de la Madeleine');
        $this->get(route('company'))->assertOk()->assertDontSee('AVENIR PHARMA');
    }
}

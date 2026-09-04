<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\ProcessStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessAndLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_parcours_d_accompagnement_s_affiche_sur_l_accueil(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);
        $this->seed(\Database\Seeders\ProcessSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Comprendre')
            ->assertSee('Maintenir')
            ->assertSee('Un équipement livré est un équipement', false);
    }

    public function test_une_etape_masquee_disparait_du_parcours(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        ProcessStep::query()->create([
            'title' => 'Étape cachée',
            'position' => 0,
            'is_active' => false,
        ]);

        $this->get('/')->assertOk()->assertDontSee('Étape cachée');
    }

    public function test_un_admin_peut_creer_une_etape(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.process.store'), [
                'title' => 'Auditer',
                'subtitle' => 'Nous faisons le point',
                'is_active' => 1,
                'position' => 0,
            ])->assertRedirect();

        $this->assertDatabaseHas('process_steps', ['title' => 'Auditer']);
    }

    public function test_la_realisation_phare_est_mise_en_avant(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Establishment::query()->create([
            'name' => 'Clinique de la Madeleine',
            'type' => 'Clinique privée',
            'city' => 'Dakar',
            'is_flagship' => true,
            'is_featured' => true,
            'is_active' => true,
            'equipments' => "Bloc opératoire\nImagerie",
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Clinique de la Madeleine')
            ->assertSee('Solutions fournies')
            ->assertSee('Accompagnement');
    }

    public function test_la_presentation_utilise_la_mise_en_page_alternee(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('wrap split', false)
            ->assertSee('split-figures', false);
    }
}

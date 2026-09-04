<?php

namespace Tests\Feature;

use App\Models\Milestone;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnersAndHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_partenaires_apparaissent_sur_le_site(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Partner::query()->create([
            'name' => 'Mindray',
            'country' => 'Chine',
            'is_active' => true,
            'is_featured' => true,
        ]);

        // Les partenaires sont désormais regroupés sur « Services & expertise ».
        $this->get('/services')->assertOk()->assertSee('Mindray');

        // L'ancienne adresse continue de fonctionner (redirection).
        $this->get('/partenaires')->assertRedirect('/services');
    }

    public function test_un_partenaire_masque_n_apparait_pas(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Partner::query()->create(['name' => 'Partenaire caché', 'is_active' => false]);

        $this->get('/')->assertOk()->assertDontSee('Partenaire caché');
    }

    public function test_l_historique_apparait_sur_la_page_entreprise(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Milestone::query()->create([
            'year' => '2015',
            'title' => 'Création de la société',
            'description' => 'Les débuts à Dakar.',
            'is_active' => true,
        ]);

        $this->get('/entreprise')
            ->assertOk()
            ->assertSee('2015')
            ->assertSee('Création de la société');
    }
}

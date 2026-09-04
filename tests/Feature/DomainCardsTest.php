<?php

namespace Tests\Feature;

use App\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainCardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_cartes_annoncent_les_equipements_du_domaine(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Domain::query()->create([
            'title' => 'Imagerie médicale',
            'subtitle' => 'Radiologie et échographie',
            'is_active' => true,
            'equipments' => [
                ['title' => 'Scanner', 'text' => 'Tomodensitomètre 32 barrettes'],
                ['title' => 'Échographe', 'text' => 'Sondes multiples'],
            ],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Imagerie médicale')
            ->assertSee('Scanner')
            ->assertSee('Échographe');
    }

    public function test_un_domaine_sans_equipement_ne_casse_pas_la_page(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        Domain::query()->create([
            'title' => 'Dialyse',
            'is_active' => true,
            'equipments' => null,
        ]);

        $this->get('/')->assertOk()->assertSee('Dialyse');
    }

    public function test_le_titre_de_la_section_est_modifiable(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        settings()->set('domains_title', 'Notre couverture technique');

        Domain::query()->create(['title' => 'Bloc opératoire', 'is_active' => true]);

        $this->get('/')->assertOk()->assertSee('Notre couverture technique');
    }
}

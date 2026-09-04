<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainPageTest extends TestCase
{
    use RefreshDatabase;

    private function jeuDEssai(): Domain
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $domain = Domain::query()->create([
            'title' => 'Imagerie médicale',
            'subtitle' => 'Radiologie et échographie',
            'description' => 'Nos équipements d’imagerie.',
            'is_active' => true,
        ]);

        $brand = Partner::query()->create([
            'name' => 'Mindray',
            'country' => 'Chine',
            'is_active' => true,
        ]);

        $domain->partners()->attach($brand, ['position' => 0]);

        return $domain;
    }

    public function test_la_page_des_domaines_affiche_domaines_et_marques(): void
    {
        $this->jeuDEssai();

        $this->get(route('domains'))
            ->assertOk()
            ->assertSee('Imagerie médicale')
            ->assertSee('Mindray');
    }

    public function test_un_domaine_masque_n_apparait_pas(): void
    {
        $domain = $this->jeuDEssai();
        $domain->update(['is_active' => false]);

        $this->get(route('domains'))
            ->assertOk()
            ->assertDontSee('Imagerie médicale');
    }

    public function test_la_page_ne_multiplie_pas_les_requetes(): void
    {
        $this->jeuDEssai();

        foreach (range(1, 8) as $i) {
            Domain::query()->create(['title' => 'Domaine '.$i, 'is_active' => true]);
        }

        DB::enableQueryLog();
        $this->get(route('domains'))->assertOk();
        $requetes = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Sans chargement anticipé, 9 domaines déclencheraient bien plus de requêtes.
        $this->assertLessThan(12, $requetes, 'Les relations ne sont pas chargées à l’avance.');
    }
}

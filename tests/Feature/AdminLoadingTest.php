<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoadingTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_indicateurs_de_chargement_sont_charges(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin')
            ->assertOk()
            ->assertSee('assets/js/admin', false);
    }

    public function test_le_script_et_les_styles_existent(): void
    {
        $this->assertFileExists(public_path('assets/js/admin.js'));

        $css = file_get_contents(public_path('assets/css/admin.css'));

        // Les trois indicateurs : barre, voile d'attente et roue des boutons.
        $this->assertStringContainsString('.load-bar', $css);
        $this->assertStringContainsString('.load-overlay', $css);
        $this->assertStringContainsString('.btn-spin', $css);
    }

    public function test_le_site_public_n_est_pas_alourdi(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        // Le script de l'administration ne doit pas être chargé par les visiteurs.
        $this->get('/')->assertOk()->assertDontSee('assets/js/admin', false);
    }
}

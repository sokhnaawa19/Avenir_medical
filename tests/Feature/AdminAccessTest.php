<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_ne_peut_pas_ouvrir_l_administration(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_un_client_ne_peut_pas_ouvrir_l_administration(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_un_administrateur_ouvre_le_tableau_de_bord(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin')
            ->assertOk();
    }
}

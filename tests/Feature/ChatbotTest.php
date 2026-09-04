<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_assistant_apparait_sur_le_site(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('chatToggle', false)
            ->assertSee('chatBox', false);
    }

    public function test_la_fenetre_est_fermee_au_chargement(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $html = $this->get('/')->getContent();

        // L'attribut « hidden » doit être présent sur la fenêtre…
        $this->assertMatchesRegularExpression('/id="chatBox"[^>]*\shidden/', $html);

        // …et le CSS doit garantir qu'il est bien respecté.
        $css = file_get_contents(public_path('assets/css/style.css'));
        $this->assertStringContainsString('[hidden]{display:none!important}', $css);
    }

    public function test_l_assistant_peut_etre_desactive(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        settings()->set('chat_enabled', '0');

        $this->get('/')->assertOk()->assertDontSee('chatToggle', false);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_publiques_repondent(): void
    {
        $this->seed();

        foreach (['/', '/entreprise', '/domaines', '/services', '/contact', '/blog', '/boutique'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_le_formulaire_de_contact_enregistre_le_message(): void
    {
        $this->seed();

        $this->post('/contact', [
            'name' => 'Awa Diop',
            'phone' => '77 123 45 67',
            'message' => 'Bonjour, je souhaite un devis.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('contact_messages', ['name' => 'Awa Diop']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Un <form> imbriqué dans un autre est fusionné par le navigateur :
 * le champ « DELETE » se retrouve dans le formulaire d'édition et le
 * bouton « Enregistrer » supprime le contenu au lieu de l'enregistrer.
 *
 * Ces tests interdisent le retour de ce défaut.
 */
class AdminFormIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        return User::factory()->admin()->create();
    }

    /** Compte la profondeur maximale d'imbrication des formulaires. */
    private function profondeurMaximale(string $html): int
    {
        preg_match_all('#</?form\b#i', $html, $balises);

        $profondeur = 0;
        $maximum = 0;

        foreach ($balises[0] as $balise) {
            $profondeur += str_starts_with($balise, '</') ? -1 : 1;
            $maximum = max($maximum, $profondeur);
        }

        return $maximum;
    }

    public function test_le_formulaire_de_formation_ne_contient_pas_de_formulaire_imbrique(): void
    {
        $admin = $this->admin();

        $formation = Training::query()->create(['title' => 'Formation test', 'is_active' => true]);
        $formation->photos()->create(['image' => 'formations/photo.webp']);

        $html = $this->actingAs($admin)
            ->get(route('admin.trainings.edit', $formation))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, $this->profondeurMaximale($html),
            'Un formulaire est imbriqué : le bouton Enregistrer risque de supprimer la formation.');
    }

    public function test_le_formulaire_de_service_ne_contient_pas_de_formulaire_imbrique(): void
    {
        $admin = $this->admin();

        $service = Service::query()->create(['title' => 'Projets clé en main', 'is_active' => true]);
        $service->photos()->create(['image' => 'projets/photo.webp']);

        $html = $this->actingAs($admin)
            ->get(route('admin.services.edit', $service))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, $this->profondeurMaximale($html));
    }

    public function test_enregistrer_une_formation_ne_la_supprime_pas(): void
    {
        $admin = $this->admin();

        $formation = Training::query()->create(['title' => 'Formation CANON', 'is_active' => true]);
        $formation->photos()->create(['image' => 'formations/photo.webp']);

        $this->actingAs($admin)->put(route('admin.trainings.update', $formation), [
            'title' => 'Formation CANON',
            'organism' => 'Canon Medical Systems',
            'is_active' => 1,
        ])->assertRedirect();

        // La formation existe toujours, et sa photo aussi.
        $this->assertDatabaseHas('trainings', ['id' => $formation->id]);
        $this->assertSame(1, $formation->fresh()->photos()->count());
    }
}

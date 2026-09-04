<?php

namespace Tests\Feature;

use App\Models\Training;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainingPhotosTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_admin_peut_ajouter_plusieurs_photos_a_une_formation(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.trainings.store'), [
            'title' => 'Maintenance des échographes',
            'organism' => 'Mindray Academy',
            'country' => 'Chine',
            'is_active' => 1,
            'photos' => [
                UploadedFile::fake()->image('technicien1.jpg'),
                UploadedFile::fake()->image('technicien2.jpg'),
            ],
        ])->assertRedirect();

        $formation = Training::query()->where('title', 'Maintenance des échographes')->first();

        $this->assertNotNull($formation);
        $this->assertCount(2, $formation->photos);
    }

    public function test_les_photos_apparaissent_sur_la_page_services(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $formation = Training::query()->create([
            'title' => 'Formation respirateurs',
            'country' => 'Allemagne',
            'is_active' => true,
        ]);

        $formation->photos()->create(['image' => 'formations/photo.webp', 'caption' => 'Nos techniciens']);

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('training-photo', false);
    }

    public function test_une_photo_peut_etre_supprimee(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $admin = User::factory()->admin()->create();
        $formation = Training::query()->create(['title' => 'Formation test', 'is_active' => true]);
        $photo = $formation->photos()->create(['image' => 'formations/photo.webp']);

        $this->actingAs($admin)
            ->delete(route('admin.trainings.photos.destroy', [$formation, $photo]))
            ->assertRedirect();

        $this->assertDatabaseMissing('content_photos', ['id' => $photo->id]);
    }
}

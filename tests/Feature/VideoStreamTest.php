<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoStreamTest extends TestCase
{
    public function test_une_video_peut_etre_lue_par_morceaux(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('videos/demo.mp4', str_repeat('a', 5000));

        // Le navigateur demande seulement le début du fichier.
        $this->get('/media/video/videos/demo.mp4', ['Range' => 'bytes=0-99'])
            ->assertStatus(206)
            ->assertHeader('Accept-Ranges', 'bytes');
    }

    public function test_une_video_absente_renvoie_une_erreur(): void
    {
        Storage::fake('public');

        $this->get('/media/video/videos/inconnue.mp4')->assertNotFound();
    }

    public function test_les_autres_fichiers_sont_refuses(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('secret.txt', 'contenu privé');

        $this->get('/media/video/secret.txt')->assertNotFound();
    }
}

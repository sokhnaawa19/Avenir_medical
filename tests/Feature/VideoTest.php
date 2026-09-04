<?php

namespace Tests\Feature;

use Tests\TestCase;

class VideoTest extends TestCase
{
    public function test_les_liens_youtube_sont_reconnus(): void
    {
        foreach ([
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ',
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'https://youtube.com/shorts/dQw4w9WgXcQ',
            'https://m.youtube.com/watch?v=dQw4w9WgXcQ&t=12s',
        ] as $url) {
            $video = video_embed($url);

            $this->assertNotNull($video, 'Lien non reconnu : '.$url);
            $this->assertSame('youtube', $video['type']);
            $this->assertStringContainsString('dQw4w9WgXcQ', $video['src']);
        }
    }

    public function test_les_fichiers_video_sont_reconnus(): void
    {
        foreach (['videos/clip.mp4', 'videos/clip.MOV', 'videos/clip.webm', 'videos/clip.m4v'] as $path) {
            $video = video_embed($path);

            $this->assertNotNull($video, 'Fichier non reconnu : '.$path);
            $this->assertSame('file', $video['type']);
            $this->assertNotEmpty($video['mime']);
        }
    }

    public function test_un_lien_inconnu_ne_casse_rien(): void
    {
        $this->assertNull(video_embed('https://exemple.com/page'));
        $this->assertNull(video_embed(null));
        $this->assertNull(video_embed(''));
    }
}

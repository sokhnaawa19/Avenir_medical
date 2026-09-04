<?php

namespace App\Http\Controllers\Concerns;

use App\Services\ImageOptimizer;
use App\Services\VideoOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Gere l'envoi des images depuis l'administration
 * (logo, photos de produits, illustrations d'articles...).
 */
trait HandlesMediaUploads
{
    /**
     * Enregistre le fichier envoye et supprime l'ancien s'il existe.
     */
    protected function storeUpload(?UploadedFile $file, string $folder, ?string $current = null): ?string
    {
        if (! $file) {
            return $current;
        }

        $this->deleteMedia($current);

        // Les images sont automatiquement allegees (redimension + WebP).
        return app(ImageOptimizer::class)->store($file, $folder);
    }

    /**
     * Enregistre la video d'un contenu (article, produit, domaine).
     *
     * Trois cas possibles :
     *  - la case « supprimer » est cochee  -> la video est retiree ;
     *  - un fichier est envoye             -> il remplace l'ancien ;
     *  - un lien YouTube/Vimeo est saisi   -> le lien est conserve.
     */
    protected function storeVideo(Request $request, ?string $current, string $folder = 'videos'): ?string
    {
        if ($request->boolean('remove_video')) {
            $this->deleteMedia($current);

            return null;
        }

        $file = $request->file('video_file');

        if ($file) {
            $this->deleteMedia($current);

            // Les videos sont allegees quand ffmpeg est disponible.
            return app(VideoOptimizer::class)->store($file, $folder);
        }

        return $request->filled('video_url') ? (string) $request->input('video_url') : $current;
    }

    /**
     * Supprime une image du disque.
     */
    protected function deleteMedia(?string $path): void
    {
        if (blank($path) || str_starts_with($path, 'http')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

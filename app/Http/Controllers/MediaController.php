<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Envoie les vidéos enregistrées sur le site.
 *
 * Pourquoi ce contrôleur ? Le serveur de test de Laravel
 * (php artisan serve) ne sait pas envoyer un fichier « par morceaux ».
 * Résultat : les vidéos ne démarrent pas et l'avance rapide ne marche pas.
 *
 * Cette page envoie le fichier correctement, y compris en plusieurs morceaux,
 * ce qui permet de lancer la lecture immédiatement et de se déplacer
 * dans la vidéo, même en développement.
 */
class MediaController extends Controller
{
    /** Formats autorisés (sécurité : rien d'autre ne peut être téléchargé). */
    private const EXTENSIONS = ['mp4', 'webm', 'ogg', 'ogv', 'm4v', 'mov'];

    public function video(Request $request, string $path): BinaryFileResponse
    {
        // Le chemin ne doit jamais sortir du dossier des fichiers du site.
        $path = ltrim(str_replace(['..', '\\'], '', $path), '/');
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        abort_unless(in_array($extension, self::EXTENSIONS, true), 404);

        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        $response = response()->file($disk->path($path), [
            'Content-Type' => $this->mimeType($extension),
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=604800',
        ]);

        // Permet au navigateur de demander seulement une partie du fichier
        // (démarrage immédiat et déplacement dans la vidéo).
        $response->setAutoLastModified();
        $response->prepare($request);

        return $response;
    }

    private function mimeType(string $extension): string
    {
        return match ($extension) {
            'webm' => 'video/webm',
            'ogg', 'ogv' => 'video/ogg',
            'mov' => 'video/quicktime',
            'm4v' => 'video/x-m4v',
            default => 'video/mp4',
        };
    }
}

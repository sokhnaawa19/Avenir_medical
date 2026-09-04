<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Allege les images envoyees depuis l'administration.
 *
 * Une photo de 5 Mo prise au telephone devient un fichier WebP de
 * quelques dizaines de kilo-octets : le site reste rapide sans effort.
 */
class ImageOptimizer
{
    /** Largeur maximale conservee (en pixels). */
    private const MAX_WIDTH = 1600;

    /** Qualite de compression (0 a 100). */
    private const QUALITY = 82;

    /** Au-dela, l'image n'est pas retravaillee (protection memoire). */
    private const MAX_PIXELS = 40_000_000;

    /**
     * Enregistre le fichier et renvoie son chemin.
     */
    public function store(UploadedFile $file, string $folder): string
    {
        if (! $this->canOptimize($file)) {
            return $file->store($folder, 'public');
        }

        try {
            $optimized = $this->optimize($file);
        } catch (\Throwable $exception) {
            Log::warning('Optimisation d’image impossible : '.$exception->getMessage());

            return $file->store($folder, 'public');
        }

        if ($optimized === null) {
            return $file->store($folder, 'public');
        }

        [$binary, $extension] = $optimized;
        $path = trim($folder, '/').'/'.Str::uuid()->toString().'.'.$extension;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    /**
     * Seuls les JPEG, PNG et WebP sont retravailles
     * (les SVG, icones et vidéos sont enregistres tels quels).
     */
    private function canOptimize(UploadedFile $file): bool
    {
        return extension_loaded('gd')
            && in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true);
    }

    /**
     * @return array{0: string, 1: string}|null  Le contenu du fichier et son extension.
     */
    private function optimize(UploadedFile $file): ?array
    {
        $path = $file->getRealPath();
        $info = @getimagesize($path);

        if ($info === false) {
            return null;
        }

        [$width, $height] = $info;

        if ($width * $height > self::MAX_PIXELS) {
            return null;
        }

        $image = match ($file->getMimeType()) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            default => null,
        };

        if (! $image) {
            return null;
        }

        // Reduction si l'image est plus large que necessaire.
        if ($width > self::MAX_WIDTH) {
            $resized = imagescale($image, self::MAX_WIDTH);

            if ($resized !== false) {
                imagedestroy($image);
                $image = $resized;
            }
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();

        if (function_exists('imagewebp')) {
            imagewebp($image, null, self::QUALITY);
            $extension = 'webp';
        } else {
            imagejpeg($image, null, self::QUALITY);
            $extension = 'jpg';
        }

        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary === '' ? null : [$binary, $extension];
    }
}

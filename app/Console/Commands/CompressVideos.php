<?php

namespace App\Console\Commands;

use App\Services\VideoOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Allege les videos deja presentes sur le serveur.
 *
 *   php artisan videos:compresser              (montre ce qui serait fait)
 *   php artisan videos:compresser --appliquer  (compresse pour de vrai)
 *
 * Le nom des fichiers ne change pas : rien a modifier en base de donnees.
 * Utile si ffmpeg n'etait pas installe au moment de l'envoi, ou pour
 * rattraper des videos mises en ligne avant cette mise a jour.
 */
class CompressVideos extends Command
{
    protected $signature = 'videos:compresser {--appliquer : Compresser réellement les fichiers}';

    protected $description = 'Allège les vidéos déjà envoyées sur le site';

    public function handle(VideoOptimizer $optimizer): int
    {
        if (! $optimizer->available()) {
            $this->error('ffmpeg est introuvable sur ce serveur.');
            $this->line('Installez-le, ou indiquez son chemin avec FFMPEG_PATH dans le fichier .env.');

            return self::FAILURE;
        }

        $disk = Storage::disk('public');
        $videos = collect($disk->allFiles())
            ->filter(fn (string $path): bool => in_array(
                strtolower(pathinfo($path, PATHINFO_EXTENSION)),
                ['mp4', 'webm', 'mov', 'm4v'],
                true
            ))
            ->filter(fn (string $path): bool => $optimizer->shouldCompress($disk->size($path)))
            ->filter(fn (string $path): bool => $optimizer->needsCompression($disk->path($path)))
            ->values();

        if ($videos->isEmpty()) {
            $this->info('Aucune vidéo à alléger : tout est déjà léger.');

            return self::SUCCESS;
        }

        $apply = $this->option('appliquer');
        $this->line($videos->count().' vidéo(s) concernée(s)'.($apply ? '' : ' — simulation, rien ne sera modifié'));
        $this->newLine();

        $before = 0;
        $after = 0;

        foreach ($videos as $path) {
            $size = (int) $disk->size($path);
            $before += $size;

            if (! $apply) {
                $this->line(sprintf('  %s  %s', $this->mo($size), $path));
                $after += $size;

                continue;
            }

            $source = tempnam(sys_get_temp_dir(), 'src');
            $target = tempnam(sys_get_temp_dir(), 'out').'.mp4';
            file_put_contents($source, $disk->get($path));

            if ($optimizer->compress($source, $target)) {
                $disk->put($path, (string) file_get_contents($target));
                $new = (int) $disk->size($path);
                $after += $new;
                $this->line(sprintf('  %s → %s  %s', $this->mo($size), $this->mo($new), $path));
            } else {
                $after += $size;
                $this->warn(sprintf('  inchangée (%s)  %s', $this->mo($size), $path));
            }

            @unlink($source);
            @unlink($target);
        }

        $this->newLine();
        $this->info($apply
            ? sprintf('Total : %s → %s', $this->mo($before), $this->mo($after))
            : sprintf('Total actuel : %s. Relancez avec --appliquer pour compresser.', $this->mo($before)));

        return self::SUCCESS;
    }

    private function mo(int $bytes): string
    {
        return round($bytes / 1048576, 1).' Mo';
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Allege les videos envoyees depuis l'administration.
 *
 * Une video de telephone ou de camera pese souvent 30 a 100 Mo : sur une
 * connexion mobile, la page devient inutilisable. Le service la reencode en
 * 1280 pixels de large, avec l'index place en tete de fichier pour que la
 * lecture demarre sans attendre le telechargement complet.
 *
 * L'outil ffmpeg n'est pas installe partout. S'il manque, la video est
 * enregistree telle quelle : l'envoi ne echoue jamais a cause de ca. La
 * commande « php artisan videos:compresser » permet alors de rattraper les
 * fichiers plus tard, ou de les traiter sur un autre serveur.
 */
class VideoOptimizer
{
    /** Largeur maximale conservee (en pixels). */
    private const MAX_WIDTH = 1280;

    /**
     * Qualite : plus le chiffre est haut, plus le fichier est leger.
     * 30 tient la route pour de la video de presentation.
     */
    private const QUALITY = 30;

    /** En dessous de cette taille, la video est deja assez legere. */
    private const MIN_SIZE = 3 * 1024 * 1024;

    /** Au-dela de ce debit, la video est trop gourmande pour du mobile. */
    private const MAX_BITRATE = 2_500_000;

    /** Une conversion longue ne doit pas bloquer l'administration. */
    private const TIMEOUT = 600;

    /**
     * Enregistre la video et renvoie son chemin.
     */
    public function store(UploadedFile $file, string $folder): string
    {
        $source = $file->getRealPath();

        if (! $this->shouldCompress($file->getSize()) || ! $this->needsCompression($source)) {
            return $file->store($folder, 'public');
        }

        $target = tempnam(sys_get_temp_dir(), 'video').'.mp4';

        if (! $this->compress($source, $target)) {
            @unlink($target);

            return $file->store($folder, 'public');
        }

        $path = trim($folder, '/').'/'.Str::uuid()->toString().'.mp4';
        Storage::disk('public')->put($path, (string) file_get_contents($target));
        @unlink($target);

        return $path;
    }

    /**
     * Reencode une video. Renvoie false si ffmpeg est absent ou en echec,
     * pour que l'appelant puisse conserver le fichier d'origine.
     */
    public function compress(string $source, string $target): bool
    {
        if (! $this->available()) {
            Log::info('ffmpeg est absent : la video est conservee telle quelle.');

            return false;
        }

        $process = new Process([
            $this->binary(),
            '-y',
            '-i', $source,
            '-vf', 'scale=\'min('.self::MAX_WIDTH.',iw)\':-2',
            '-c:v', 'libx264',
            '-crf', (string) self::QUALITY,
            '-preset', 'medium',
            '-c:a', 'aac',
            '-b:a', '96k',
            '-movflags', '+faststart',
            $target,
        ]);

        $process->setTimeout(self::TIMEOUT);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            Log::warning('Compression video impossible : '.$exception->getMessage());

            return false;
        }

        // Une conversion qui produit un fichier plus lourd n'a aucun interet.
        return is_file($target)
            && filesize($target) > 0
            && filesize($target) < filesize($source);
    }

    /** ffmpeg est-il utilisable sur ce serveur ? */
    public function available(): bool
    {
        try {
            $process = new Process([$this->binary(), '-version']);
            $process->setTimeout(15);
            $process->run();

            return $process->isSuccessful();
        } catch (\Throwable) {
            return false;
        }
    }

    /** Au-dela de quelques Mo, la compression vaut le coup. */
    public function shouldCompress(int|false|null $size): bool
    {
        return is_int($size) && $size >= self::MIN_SIZE;
    }

    /**
     * La video merite-t-elle d'etre retravaillee ?
     *
     * Une video deja en 1280 et deja peu gourmande est laissee tranquille :
     * la recompresser ne ferait que degrader l'image pour quelques centaines
     * de kilo-octets. Sans ffprobe, on repond oui et c'est le poids seul qui
     * decide.
     */
    public function needsCompression(string $path): bool
    {
        $probe = new Process([
            $this->probe(),
            '-v', 'error',
            '-select_streams', 'v:0',
            '-show_entries', 'stream=width',
            '-show_entries', 'format=bit_rate',
            '-of', 'default=noprint_wrappers=1:nokey=1',
            $path,
        ]);

        try {
            $probe->setTimeout(30);
            $probe->run();

            if (! $probe->isSuccessful()) {
                return true;
            }

            $lines = preg_split('/\s+/', trim($probe->getOutput())) ?: [];
            $width = (int) ($lines[0] ?? 0);
            $bitrate = (int) ($lines[1] ?? 0);

            if ($width === 0) {
                return true;
            }

            return $width > self::MAX_WIDTH || $bitrate > self::MAX_BITRATE;
        } catch (\Throwable) {
            return true;
        }
    }

    /** Chemin de ffmpeg, modifiable par FFMPEG_PATH dans le fichier .env. */
    private function binary(): string
    {
        return (string) config('media.ffmpeg', 'ffmpeg');
    }

    /** ffprobe accompagne toujours ffmpeg. */
    private function probe(): string
    {
        return (string) config('media.ffprobe', 'ffprobe');
    }
}

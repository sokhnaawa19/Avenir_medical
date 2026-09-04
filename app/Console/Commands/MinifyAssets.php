<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Cree les versions allegees des fichiers CSS et JavaScript.
 *
 * A lancer apres chaque modification du design :  php artisan site:minify
 */
class MinifyAssets extends Command
{
    protected $signature = 'site:minify';

    protected $description = 'Compresse les fichiers CSS et JavaScript du site';

    /** @var array<int, string> */
    private array $files = [
        'assets/css/style.css',
        'assets/css/admin.css',
        'assets/js/script.js',
        'assets/js/admin.js',
    ];

    public function handle(): int
    {
        foreach ($this->files as $file) {
            $source = public_path($file);

            if (! is_file($source)) {
                $this->warn('Fichier introuvable : '.$file);

                continue;
            }

            $content = (string) file_get_contents($source);
            $minified = str_ends_with($file, '.css')
                ? $this->minifyCss($content)
                : $this->minifyJs($content);

            $target = public_path(preg_replace('/\.(css|js)$/', '.min.$1', $file));
            file_put_contents($target, $minified);

            $saved = 100 - (int) round(strlen($minified) / max(strlen($content), 1) * 100);
            $this->info(basename($target).' — '.$saved.' % plus léger');
        }

        return self::SUCCESS;
    }

    private function minifyCss(string $css): string
    {
        $css = preg_replace('#/\*(?!!).*?\*/#s', '', $css) ?? $css;
        $css = preg_replace('/\s+/', ' ', $css) ?? $css;
        $css = str_replace([' {', '{ ', ' }', '} ', ' : ', ': ', ' ; ', '; ', ' , ', ', '], ['{', '{', '}', '}', ':', ':', ';', ';', ',', ','], $css);

        return trim(str_replace(';}', '}', $css));
    }

    /**
     * Compression prudente du JavaScript.
     *
     * On retire les commentaires et les lignes vides, sans jamais toucher
     * au contenu des chaines de caracteres ni fusionner les lignes :
     * le code reste strictement equivalent a l'original.
     */
    private function minifyJs(string $js): string
    {
        $out = '';
        $length = strlen($js);
        $i = 0;
        $quote = null;

        while ($i < $length) {
            $char = $js[$i];
            $next = $js[$i + 1] ?? '';

            // A l'interieur d'une chaine de caracteres : on recopie tel quel.
            if ($quote !== null) {
                $out .= $char;

                if ($char === '\\') {
                    $out .= $next;
                    $i += 2;

                    continue;
                }

                if ($char === $quote) {
                    $quote = null;
                }

                $i++;

                continue;
            }

            // Debut d'une chaine de caracteres.
            if ($char === '"' || $char === "'" || $char === '`') {
                $quote = $char;
                $out .= $char;
                $i++;

                continue;
            }

            // Commentaire sur plusieurs lignes.
            if ($char === '/' && $next === '*') {
                $end = strpos($js, '*/', $i + 2);
                $i = $end === false ? $length : $end + 2;

                continue;
            }

            // Commentaire de fin de ligne.
            if ($char === '/' && $next === '/') {
                $end = strpos($js, "\n", $i);
                $i = $end === false ? $length : $end;

                continue;
            }

            $out .= $char;
            $i++;
        }

        // Suppression des lignes vides et de l'indentation.
        $lines = array_filter(
            array_map('rtrim', explode("\n", $out)),
            static fn (string $line): bool => trim($line) !== ''
        );

        return implode("\n", array_map(static fn (string $line): string => ltrim($line), $lines));
    }
}

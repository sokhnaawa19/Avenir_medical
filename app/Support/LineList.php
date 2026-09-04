<?php

namespace App\Support;

/**
 * Convertit un champ « une ligne = un élément » de l'administration
 * en tableau exploitable dans les vues, et inversement.
 *
 * Format attendu dans l'administration :
 *   Titre de la ligne | Explication facultative
 *
 * Cela évite d'imposer un éditeur compliqué : l'équipe saisit
 * simplement une ligne par élément.
 */
class LineList
{
    /**
     * Texte saisi  ->  [['title' => ..., 'text' => ...], ...]
     *
     * @return array<int, array{title: string, text: string}>
     */
    public static function toPairs(?string $value): array
    {
        return array_values(array_filter(array_map(
            static function (string $line): ?array {
                $line = trim($line);

                if ($line === '') {
                    return null;
                }

                $parts = array_map('trim', explode('|', $line, 2));

                return [
                    'title' => $parts[0],
                    'text' => $parts[1] ?? '',
                ];
            },
            preg_split('/\r\n|\r|\n/', (string) $value) ?: []
        )));
    }

    /**
     * Texte saisi  ->  ['élément 1', 'élément 2', ...]
     *
     * @return array<int, string>
     */
    public static function toList(?string $value): array
    {
        return array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', (string) $value) ?: []
        ), static fn (string $line): bool => $line !== ''));
    }

    /**
     * Tableau enregistré  ->  texte réaffiché dans l'administration.
     *
     * @param  array<int, mixed>|null  $items
     */
    public static function toText(?array $items): string
    {
        if (empty($items)) {
            return '';
        }

        return implode("\n", array_map(
            static function ($item): string {
                if (is_string($item)) {
                    return $item;
                }

                if (! is_array($item)) {
                    return '';
                }

                $title = (string) ($item['title'] ?? '');
                $text = (string) ($item['text'] ?? '');

                return $text === '' ? $title : $title.' | '.$text;
            },
            $items
        ));
    }
}

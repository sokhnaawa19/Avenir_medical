<?php

namespace App\Services\Translation;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Traduit du texte via un service externe (DeepL, Google ou Claude).
 *
 * Le service et la clé se règlent dans le fichier .env :
 *
 *   TRANSLATE_DRIVER=deepl
 *   TRANSLATE_KEY=votre-cle
 *
 * Les noms de marques et les références ne sont jamais traduits :
 * ils sont protégés avant l'envoi puis remis en place après.
 */
class Translator
{
    public function __construct(
        private readonly string $driver,
        private readonly ?string $key,
        private readonly array $protected = [],
    ) {}

    public static function make(): self
    {
        return new self(
            config('services.translate.driver', 'deepl'),
            config('services.translate.key'),
            config('services.translate.protected', []),
        );
    }

    public function configured(): bool
    {
        return filled($this->key);
    }

    /** Traduit un texte du français vers la langue demandée. */
    public function translate(string $text, string $target = 'en'): string
    {
        if (blank(trim($text))) {
            return $text;
        }

        if (! $this->configured()) {
            throw new RuntimeException(
                "Aucune clé de traduction n'est configurée. "
                ."Ajoutez TRANSLATE_DRIVER et TRANSLATE_KEY dans le fichier .env."
            );
        }

        // Un texte qui n'est QUE le nom d'une marque reste tel quel :
        // « AVENIR MEDICAL » ne doit jamais devenir « MEDICAL FUTURE ».
        if ($this->estUnNomPropre($text)) {
            return $text;
        }

        [$protege, $marqueurs] = $this->protect($text);

        $traduit = match ($this->driver) {
            'deepl' => $this->viaDeepl($protege, $target),
            'google' => $this->viaGoogle($protege, $target),
            'claude' => $this->viaClaude($protege, $target),
            default => throw new RuntimeException("Service de traduction inconnu : {$this->driver}"),
        };

        return $this->restore($traduit, $marqueurs);
    }

    /** Le texte se réduit-il à un nom de marque protégé ? */
    private function estUnNomPropre(string $text): bool
    {
        $nettoye = trim($text);

        foreach ($this->protected as $mot) {
            if (mb_strtolower($nettoye) === mb_strtolower($mot)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Remplace les mots protégés par des marqueurs neutres.
     *
     * @return array{0: string, 1: array<string, string>}
     */
    private function protect(string $text): array
    {
        $marqueurs = [];

        foreach ($this->protected as $index => $mot) {
            $marqueur = '{{'.$index.'}}';

            $remplace = preg_replace(
                '/\b'.preg_quote($mot, '/').'\b/iu',
                $marqueur,
                $text,
                -1,
                $nombre
            );

            if ($nombre > 0) {
                $marqueurs[$marqueur] = $mot;
                $text = $remplace;
            }
        }

        return [$text, $marqueurs];
    }

    /** @param array<string, string> $marqueurs */
    private function restore(string $text, array $marqueurs): string
    {
        return str_replace(array_keys($marqueurs), array_values($marqueurs), $text);
    }

    private function viaDeepl(string $text, string $target): string
    {
        $url = str_starts_with($this->key, 'free:') || str_ends_with((string) $this->key, ':fx')
            ? 'https://api-free.deepl.com/v2/translate'
            : 'https://api.deepl.com/v2/translate';

        $reponse = Http::asForm()
            ->withHeaders(['Authorization' => 'DeepL-Auth-Key '.$this->key])
            ->timeout(30)
            ->post($url, [
                'text' => $text,
                'source_lang' => 'FR',
                'target_lang' => strtoupper($target),
                'preserve_formatting' => '1',
            ])
            ->throw()
            ->json();

        return $reponse['translations'][0]['text'] ?? $text;
    }

    private function viaGoogle(string $text, string $target): string
    {
        $reponse = Http::timeout(30)
            ->post('https://translation.googleapis.com/language/translate/v2?key='.$this->key, [
                'q' => $text,
                'source' => 'fr',
                'target' => $target,
                'format' => 'text',
            ])
            ->throw()
            ->json();

        return $reponse['data']['translations'][0]['translatedText'] ?? $text;
    }

    private function viaClaude(string $text, string $target): string
    {
        $langue = $target === 'en' ? 'anglais' : $target;

        $reponse = Http::withHeaders([
            'x-api-key' => $this->key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])
            ->timeout(60)
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-sonnet-4-6',
                'max_tokens' => 2000,
                'system' => "Tu traduis du contenu d'un site d'équipements médicaux, du français vers l'{$langue}. "
                    ."Réponds uniquement par la traduction, sans commentaire ni guillemets. "
                    ."Conserve la mise en forme, les retours à la ligne et les marqueurs {{0}}, {{1}} tels quels. "
                    ."Emploie le vocabulaire médical et technique approprié.",
                'messages' => [['role' => 'user', 'content' => $text]],
            ])
            ->throw()
            ->json();

        return trim($reponse['content'][0]['text'] ?? $text);
    }
}

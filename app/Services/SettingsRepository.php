<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Lit et enregistre les reglages du site.
 *
 * Les valeurs sont stockees en base de donnees puis gardees en cache
 * pour ne pas interroger la base a chaque affichage de page.
 */
class SettingsRepository
{
    private const CACHE_KEY = 'settings.all';

    /** @var array<string, string|null>|null */
    private array $values = [];

    /**
     * Recupere une valeur de reglage.
     *
     * - Si le reglage a ete enregistre, sa valeur est renvoyee telle quelle
     *   (un champ vide reste vide : c'est un choix de l'administrateur).
     * - S'il n'a jamais ete enregistre, on prend la valeur par defaut
     *   declaree dans config/settings.php, puis celle passee a l'appel.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $values = $this->all();

        if (array_key_exists($key, $values)) {
            $value = $values[$key];

            return ($value === null || $value === '') ? $default : $value;
        }

        return $this->schemaDefault($key) ?? $default;
    }

    public function boolean(string $key, bool $default = false): bool
    {
        $value = $this->get($key);

        if ($value === null || $value === '') {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function integer(string $key, int $default = 0): int
    {
        $value = $this->get($key);

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * Adresse publique d'une image enregistree (logo, photo d'accueil...).
     */
    public function image(string $key, ?string $fallback = null): ?string
    {
        $path = $this->get($key);

        if (blank($path)) {
            return $fallback;
        }

        if (str_starts_with((string) $path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        $locale = app()->getLocale();

        if (isset($this->values[$locale])) {
            return $this->values[$locale];
        }

        return $this->values[$locale] = Cache::rememberForever(
            self::CACHE_KEY.':'.$locale,
            function () use ($locale): array {
                if (! $this->tableExists()) {
                    return [];
                }

                $defaut = config('app.fallback_locale', 'fr');

                // En français, la valeur d'origine suffit.
                if ($locale === $defaut) {
                    return Setting::query()->pluck('value', 'key')->all();
                }

                // Dans les autres langues, la traduction remplace la valeur
                // quand elle existe (sinon on garde le français).
                return Setting::query()
                    ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
                    ->get()
                    ->mapWithKeys(function (Setting $reglage) use ($locale): array {
                        $traduction = $reglage->translationFor('value', $locale);

                        return [$reglage->key => filled($traduction) ? $traduction : $reglage->value];
                    })
                    ->all();
            }
        );
    }

    public function set(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => is_bool($value) ? ($value ? '1' : '0') : $value,
                'group' => $this->schemaGroup($key) ?? 'general',
                'type' => $this->schemaType($key) ?? 'text',
            ]
        );

        $this->flush();
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function forget(string $key): void
    {
        Setting::query()->where('key', $key)->delete();

        $this->flush();
    }

    public function flush(): void
    {
        $this->values = [];

        foreach (config('app.locales', ['fr']) as $langue) {
            Cache::forget(self::CACHE_KEY.':'.$langue);
        }

        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Description complete des reglages (groupes et champs).
     *
     * @return array<string, array<string, mixed>>
     */
    public function schema(): array
    {
        return config('settings', []);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function fields(): array
    {
        $fields = [];

        foreach ($this->schema() as $group => $definition) {
            foreach ($definition['fields'] ?? [] as $key => $field) {
                $fields[$key] = $field + ['group' => $group];
            }
        }

        return $fields;
    }

    private function schemaDefault(string $key): mixed
    {
        return $this->fields()[$key]['default'] ?? null;
    }

    private function schemaType(string $key): ?string
    {
        return $this->fields()[$key]['type'] ?? null;
    }

    private function schemaGroup(string $key): ?string
    {
        return $this->fields()[$key]['group'] ?? null;
    }

    private function tableExists(): bool
    {
        try {
            return Setting::query()->getConnection()->getSchemaBuilder()->hasTable('settings');
        } catch (\Throwable) {
            return false;
        }
    }
}

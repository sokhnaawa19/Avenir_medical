<?php

namespace App\Models\Concerns;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Rend un modèle traduisible.
 *
 * Le modèle déclare la liste de ses champs traduisibles :
 *
 *     protected array $translatable = ['name', 'description'];
 *
 * On lit ensuite la valeur avec $produit->t('name') : en français
 * la valeur d'origine est renvoyée, en anglais la traduction si
 * elle existe, sinon le français (jamais de texte vide).
 */
trait HasTranslations
{
    /**
     * Charge les traductions en même temps que les contenus.
     *
     * Sans cela, afficher 30 produits en anglais déclencherait
     * 30 requêtes supplémentaires. En français, rien n'est chargé.
     */
    public static function bootHasTranslations(): void
    {
        static::addGlobalScope('avec-traductions', function ($query): void {
            if (app()->getLocale() !== config('app.fallback_locale', 'fr')) {
                $query->with('translations');
            }
        });
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * @return array<int, string>
     */
    public function translatableFields(): array
    {
        return $this->translatable ?? [];
    }


    /**
     * Lecture transparente des champs traduisibles.
     *
     * En français (langue de référence) la valeur d'origine est renvoyée.
     * Dans une autre langue, la traduction la remplace si elle existe.
     * Les vues n'ont donc rien à changer : $domaine->title suffit.
     */
    public function getAttribute($key)
    {
        $valeur = parent::getAttribute($key);

        if (! is_string($valeur) || $valeur === '') {
            return $valeur;
        }

        $locale = app()->getLocale();

        if ($locale === config('app.fallback_locale', 'fr')
            || ! in_array($key, $this->translatableFields(), true)) {
            return $valeur;
        }

        $traduction = $this->translationFor($key, $locale);

        return filled($traduction) ? $traduction : $valeur;
    }

    /** La valeur d'origine (française), quelle que soit la langue affichée. */
    public function raw(string $field): mixed
    {
        return $this->getRawOriginal($field) ?? parent::getAttribute($field);
    }

    /** La valeur d'un champ dans la langue affichée. */
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $origine = $this->getAttribute($field);

        // Le français est la langue de référence : rien à chercher.
        if ($locale === config('app.fallback_locale', 'fr')) {
            return $origine;
        }

        $traduction = $this->translationFor($field, $locale);

        return filled($traduction) ? $traduction : $origine;
    }

    /** La traduction enregistrée, sans repli sur le français. */
    public function translationFor(string $field, string $locale): ?string
    {
        // On charge toutes les traductions de l'élément d'un coup :
        // sans cela, chaque champ déclencherait sa propre requête.
        if (! $this->relationLoaded('translations')) {
            $this->setRelation('translations', $this->translations()->get());
        }

        $collection = $this->translations;

        return $collection
            ->first(fn (Translation $t): bool => $t->locale === $locale && $t->field === $field)
            ?->value;
    }

    /** Enregistre (ou met à jour) la traduction d'un champ. */
    public function setTranslation(string $field, string $locale, ?string $value, bool $automatic = true): void
    {
        $this->translations()->updateOrCreate(
            ['locale' => $locale, 'field' => $field],
            ['value' => $value, 'is_automatic' => $automatic]
        );
    }

    /** Les champs encore sans traduction dans cette langue. */
    public function missingTranslations(string $locale): array
    {
        return array_values(array_filter(
            $this->translatableFields(),
            fn (string $field): bool => filled($this->getAttribute($field))
                && blank($this->translationFor($field, $locale))
        ));
    }
}

<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Genere automatiquement une adresse lisible (slug) unique
 * a partir d'un champ du modele : "Lit medicalise" => "lit-medicalise".
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model): void {
            $source = $model->slugSource();

            if (blank($model->slug) && filled($model->{$source})) {
                $model->slug = $model->generateUniqueSlug($model->{$source});
            }
        });
    }

    public function slugSource(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'name';
    }

    public function generateUniqueSlug(string $value): string
    {
        $base = Str::slug($value) ?: Str::random(8);
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasPhotos;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Un établissement de santé équipé par AVENIR MEDICAL.
 * C'est ce que le site appelle « nos références ».
 */
class Establishment extends Model
{
    use HasPhotos;
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['type', 'description', 'equipments'];

    protected $fillable = [
        'name', 'slug', 'type', 'city', 'country', 'logo', 'image',
        'description', 'equipments', 'year',
        'is_flagship', 'is_featured', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_flagship' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    /** Les réalisations mises en avant sur la page d'accueil. */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /** Les établissements équipés entièrement (réalisations phares). */
    public function scopeFlagship(Builder $query): Builder
    {
        return $query->where('is_flagship', true);
    }

    /** Localisation lisible : « Dakar, Sénégal ». */
    public function location(): string
    {
        return collect([$this->city, $this->country])->filter()->implode(', ');
    }

    /**
     * Les équipements installés, un par ligne dans l'administration.
     *
     * @return array<int, string>
     */
    public function equipmentList(): array
    {
        $lignes = preg_split('/\r\n|\r|\n/', (string) $this->equipments) ?: [];

        return array_values(array_filter(array_map('trim', $lignes)));
    }

    public function initials(): string
    {
        $mots = preg_split('/\s+/', trim($this->name)) ?: [];
        $lettres = array_map(
            fn (string $mot): string => mb_strtoupper(mb_substr($mot, 0, 1)),
            array_slice($mots, 0, 2)
        );

        return implode('', $lettres);
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Une entreprise du groupe. AVENIR MEDICAL est la maison mère,
 * les filiales interviennent sur des métiers complémentaires.
 */
class Subsidiary extends Model
{
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['tagline', 'activity', 'description'];

    protected $fillable = [
        'name', 'slug', 'tagline', 'activity', 'logo', 'image',
        'website', 'color', 'founded_year', 'description', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
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

<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Partner extends Model
{
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['description', 'exclusivity_scope'];

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'website',
        'country',
        'description',
        'is_featured',
        'is_exclusive',
        'exclusivity_scope',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_exclusive' => 'boolean',
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    /** Partenaires dont nous sommes le représentant exclusif. */
    public function scopeExclusive(Builder $query): Builder
    {
        return $query->where('is_exclusive', true);
    }

    /** Mention affichée sur le badge : « Exclusivité Afrique de l'Ouest ». */
    public function exclusivityLabel(): string
    {
        return filled($this->exclusivity_scope)
            ? 'Exclusivité '.$this->exclusivity_scope
            : 'Partenaire exclusif';
    }

    /** Partenaires affiches sur la page d'accueil. */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Les domaines d'intervention equipes par cette marque,
     * avec les gammes fournies dans chacun d'eux.
     */
    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(Domain::class)
            ->withPivot(['ranges', 'position'])
            ->orderBy('domain_partner.position')
            ->orderBy('domains.position');
    }

    /** Initiales, utilisees quand aucun logo n'a ete envoye. */
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name)) ?: [];
        $letters = array_map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters);
    }
}

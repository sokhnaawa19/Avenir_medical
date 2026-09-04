<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Domain extends Model
{
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['title', 'subtitle', 'intro', 'description', 'equipments'];

    protected string $slugSource = 'title';

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'icon',
        'intro',
        'description',
        'equipments',
        'image',
        'video_url',
        'in_gallery',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'equipments' => 'array',
            'in_gallery' => 'boolean',
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function scopeInGallery(Builder $query): Builder
    {
        return $query->where('in_gallery', true);
    }

    /** Les produits rattachés à ce domaine. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Les marques qui équipent ce domaine, avec les gammes fournies.
     *
     * Le lien est saisi depuis Administration → Partenaires.
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class)
            ->withPivot(['ranges', 'position'])
            ->orderBy('domain_partner.position')
            ->orderBy('partners.name');
    }

    /**
     * Les marques visibles de ce domaine, prêtes à être affichées.
     *
     * @return Collection<int, Partner>
     */
    public function brandList(): Collection
    {
        $partners = $this->relationLoaded('partners') ? $this->partners : $this->partners()->get();

        return $partners->where('is_active', true)->values();
    }

    /**
     * Les fabricants présents dans ce domaine, sans doublon.
     *
     * @return Collection<int, Partner>
     */
    public function brands(): Collection
    {
        return $this->products()
            ->with('brand')
            ->get()
            ->pluck('brand')
            ->filter()
            ->unique('id')
            ->sortBy('position')
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Raccourcis d'affichage
    |--------------------------------------------------------------------------
    */

    /**
     * Icône du domaine, avec une valeur de repli pour ne jamais avoir de vide.
     *
     * Le nom de la méthode ne doit surtout pas être « icon() » : Laravel la
     * prendrait pour un accesseur de la colonne du même nom et l'appellerait
     * en boucle.
     */
    public function displayIcon(): string
    {
        return $this->icon ?: '🏥';
    }

    /** Accroche affichée en tête de page : l'intro, sinon le sous-titre. */
    public function headline(): string
    {
        return (string) ($this->intro ?: $this->subtitle ?: '');
    }

    /**
     * Les équipements types de ce domaine.
     *
     * @return array<int, array{title: string, text: string}>
     */
    public function equipmentList(): array
    {
        return $this->normalisePairs($this->equipments);
    }

    /** Y a-t-il assez de contenu rédigé pour que la page soit riche ? */
    public function hasRichContent(): bool
    {
        return $this->equipmentList() !== [];
    }

    /** Description courte pour les moteurs de recherche et les réseaux sociaux. */
    public function metaDescription(): string
    {
        $source = $this->intro ?: $this->description ?: $this->subtitle;

        return Str::limit(strip_tags((string) $source), 155);
    }

    /**
     * @param  array<int, mixed>|null  $items
     * @return array<int, array{title: string, text: string}>
     */
    private function normalisePairs(?array $items): array
    {
        return array_values(array_filter(array_map(
            static function ($item): ?array {
                if (is_string($item)) {
                    $item = trim($item);

                    return $item === '' ? null : ['title' => $item, 'text' => ''];
                }

                if (! is_array($item)) {
                    return null;
                }

                $title = trim((string) ($item['title'] ?? ''));

                return $title === '' ? null : ['title' => $title, 'text' => trim((string) ($item['text'] ?? ''))];
            },
            $items ?? []
        )));
    }
}

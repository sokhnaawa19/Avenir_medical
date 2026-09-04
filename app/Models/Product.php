<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['name', 'short_description', 'description'];

    protected $fillable = [
        'category_id',
        'domain_id',
        'partner_id',
        'name',
        'slug',
        'reference',
        'short_description',
        'description',
        'price',
        'units_per_box',
        'box_label',
        'old_price',
        'image',
        'video_url',
        'emoji',
        'stock',
        'is_featured',
        'is_active',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'old_price' => 'integer',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }


    /**
     * Le produit est-il vendu au carton ?
     */
    public function isSoldByBox(): bool
    {
        return (int) $this->units_per_box > 1;
    }

    /**
     * Le prix réellement pratiqué : celui du carton s'il y en a un,
     * sinon le prix unitaire. C'est ce prix qui s'affiche partout et
     * qui est utilisé dans le panier.
     */
    public function sellingPrice(): int
    {
        return $this->isSoldByBox()
            ? (int) round($this->price * $this->units_per_box)
            : (int) $this->price;
    }

    /** L'ancien prix, mis à la même échelle (pour les promotions). */
    public function sellingOldPrice(): ?int
    {
        if (blank($this->old_price)) {
            return null;
        }

        return $this->isSoldByBox()
            ? (int) round($this->old_price * $this->units_per_box)
            : (int) $this->old_price;
    }

    /** Ce qu'on vend : « Carton de 50 », « Boîte de 100 »… */
    public function packagingLabel(): ?string
    {
        if (! $this->isSoldByBox()) {
            return null;
        }

        return trim(($this->box_label ?: 'Carton').' de '.$this->units_per_box);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Le domaine d'intervention (imagerie, bloc opératoire...). */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /** Le fabricant du produit, choisi parmi les partenaires. */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Recherche simple sur le nom, la reference et la description courte.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term): void {
            $builder->where('name', 'like', '%'.$term.'%')
                ->orWhere('reference', 'like', '%'.$term.'%')
                ->orWhere('short_description', 'like', '%'.$term.'%');
        });
    }

    public function isOutOfStock(): bool
    {
        return $this->stock !== null && $this->stock <= 0;
    }

    public function hasDiscount(): bool
    {
        return $this->old_price !== null && $this->old_price > $this->price;
    }
}

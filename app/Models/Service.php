<?php

namespace App\Models;

use App\Models\Concerns\HasPhotos;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasPhotos;
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['title', 'description'];

    protected string $slugSource = 'title';

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'description',
        'image',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }
}

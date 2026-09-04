<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Une photo de la galerie : événement, salon, installation, formation…
 */
class GalleryPhoto extends Model
{
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['title', 'caption'];

    protected $fillable = [
        'title', 'album', 'image', 'caption', 'taken_at', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }
}

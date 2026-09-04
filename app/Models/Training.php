<?php

namespace App\Models;

use App\Models\Concerns\HasPhotos;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Une formation suivie par les techniciens, souvent à l'étranger,
 * chez le fabricant ou dans un institut spécialisé.
 */
class Training extends Model
{
    use HasPhotos;
    use HasSlug;
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['title', 'description'];

    protected string $slugSource = 'title';

    protected $fillable = [
        'title', 'slug', 'organism', 'country', 'city', 'year',
        'participants', 'image', 'description', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'participants' => 'integer',
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }


    public function location(): string
    {
        return collect([$this->city, $this->country])->filter()->implode(', ');
    }
}

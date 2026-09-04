<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Une etape de l'histoire de l'entreprise (annee + evenement).
 */
class Milestone extends Model
{
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['title', 'description'];

    protected $fillable = [
        'year',
        'title',
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

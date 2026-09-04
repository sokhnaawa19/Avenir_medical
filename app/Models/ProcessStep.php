<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Une étape du parcours d'accompagnement.
 *
 * C'est ce qui relie entre eux les services, les formations et le SAV :
 * on montre au visiteur comment son besoin devient une solution.
 */
class ProcessStep extends Model
{
    use HasTranslations;
    use Sortable;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['title', 'subtitle', 'description'];

    protected $fillable = [
        'title', 'subtitle', 'description', 'icon', 'image', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    /** Numéro affiché : 01, 02, 03… */
    public function number(int $index): string
    {
        return str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
    }
}

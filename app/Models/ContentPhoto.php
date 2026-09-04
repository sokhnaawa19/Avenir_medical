<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Une photo appartenant à un contenu : formation, service, référence…
 */
class ContentPhoto extends Model
{
    use HasTranslations;

    /** Champs proposés à la traduction. */
    protected array $translatable = ['caption'];

    protected $fillable = ['image', 'caption', 'position'];

    protected function casts(): array
    {
        return ['position' => 'integer'];
    }

    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }
}

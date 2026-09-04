<?php

namespace App\Models\Concerns;

use App\Models\ContentPhoto;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Donne une galerie de photos à un contenu.
 */
trait HasPhotos
{
    public function photos(): MorphMany
    {
        return $this->morphMany(ContentPhoto::class, 'photoable')
            ->orderBy('position')
            ->orderBy('id');
    }
}

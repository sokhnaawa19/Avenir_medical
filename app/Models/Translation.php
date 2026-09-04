<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * La traduction d'un champ précis d'un contenu précis.
 */
class Translation extends Model
{
    protected $fillable = ['locale', 'field', 'value', 'is_automatic'];

    protected function casts(): array
    {
        return ['is_automatic' => 'boolean'];
    }

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }
}

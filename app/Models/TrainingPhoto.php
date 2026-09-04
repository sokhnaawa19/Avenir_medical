<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Une photo prise pendant une formation.
 */
class TrainingPhoto extends Model
{
    protected $fillable = ['training_id', 'image', 'caption', 'position'];

    protected function casts(): array
    {
        return ['position' => 'integer'];
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}

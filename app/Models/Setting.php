<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasTranslations;

    /** La valeur du réglage peut être traduite. */
    protected array $translatable = ['value'];

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];
}
